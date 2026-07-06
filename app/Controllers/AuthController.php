<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\App;
use App\Core\Controller;
use App\Core\JwtService;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use App\Models\UserModel;

final class AuthController extends Controller
{
    private UserModel  $userModel;
    private JwtService $jwt;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->jwt       = new JwtService();
    }

    // -----------------------------------------------------------------------
    // GET /auth/register  – show registration form
    // -----------------------------------------------------------------------

    public function showRegister(): void
    {
        $this->view('auth/register', ['title' => 'Đăng ký – ' . App::NAME], 'auth');
    }

    // -----------------------------------------------------------------------
    // GET /auth/login  – show login form
    // -----------------------------------------------------------------------

    public function showLogin(): void
    {
        $this->view('auth/login', ['title' => 'Đăng nhập – ' . App::NAME], 'auth');
    }

    // -----------------------------------------------------------------------
    // POST /auth/register
    // -----------------------------------------------------------------------

    public function register(): void
    {
        try {
            $name     = trim($_POST['name']     ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            $this->validateRegistration($name, $email, $password);

            if ($this->userModel->findByEmail($email) !== null) {
                throw AppException::from(ErrorCode::EMAIL_ALREADY_EXISTS);
            }

            $hash   = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $userId = $this->userModel->create($name, $email, $hash);

            $this->jsonSuccess([
                'message' => 'Đăng ký thành công.',
                'user'    => ['id' => $userId, 'name' => $name, 'email' => $email],
            ], 201);

        } catch (AppException $e) {
            $this->jsonException($e);
        }
    }

    // -----------------------------------------------------------------------
    // POST /auth/login
    // -----------------------------------------------------------------------

    public function login(): void
    {
        try {
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                throw AppException::from(ErrorCode::EMAIL_OR_PASSWORD_EMPTY);
            }

            $user = $this->userModel->findByEmail($email);

            if ($user === null || !password_verify($password, $user['password_hash'])) {
                throw AppException::from(ErrorCode::INVALID_CREDENTIALS);
            }

            $accessToken  = $this->jwt->generateAccessToken((int) $user['id'], $user['email']);
            $refreshToken = $this->jwt->generateRefreshToken((int) $user['id']);

            $this->userModel->storeRefreshToken(
                (int) $user['id'],
                hash('sha256', $refreshToken),
                time() + App::REFRESH_TOKEN_TTL,
            );

            $this->setAccessTokenCookie($accessToken);
            $this->setRefreshTokenCookie($refreshToken);

            $this->jsonSuccess([
                'message'      => 'Đăng nhập thành công.',
                'access_token' => $accessToken,
                'user'         => [
                    'id'    => (int) $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                ],
            ]);

        } catch (AppException $e) {
            $this->jsonException($e);
        }
    }

    // -----------------------------------------------------------------------
    // POST /auth/logout
    // -----------------------------------------------------------------------

    public function logout(): void
    {
        $refreshToken = $_COOKIE['refresh_token'] ?? '';

        if ($refreshToken !== '') {
            $this->userModel->deleteRefreshToken(hash('sha256', $refreshToken));
        }

        $this->clearTokenCookies();

        $this->jsonSuccess(['message' => 'Đăng xuất thành công.']);
    }

    // -----------------------------------------------------------------------
    // POST /auth/refresh  – issue new access token via refresh token
    // -----------------------------------------------------------------------

    public function refresh(): void
    {
        try {
            $refreshToken = $_COOKIE['refresh_token'] ?? '';

            if ($refreshToken === '') {
                throw AppException::from(ErrorCode::REFRESH_TOKEN_MISSING);
            }

            // Verify JWT signature & expiry (throws AppException on failure)
            $payload = $this->jwt->decode($refreshToken, App::jwtSecret());

            if (($payload['type'] ?? '') !== 'refresh') {
                throw AppException::from(ErrorCode::TOKEN_TYPE_INVALID);
            }

            $tokenHash = hash('sha256', $refreshToken);
            $stored    = $this->userModel->findRefreshToken($tokenHash);

            if ($stored === null) {
                throw AppException::from(ErrorCode::REFRESH_TOKEN_REVOKED);
            }

            $userId = (int) $payload['sub'];
            $user   = $this->userModel->findById($userId);

            if ($user === null) {
                throw AppException::from(ErrorCode::USER_NOT_FOUND);
            }

            // Rotate tokens
            $this->userModel->deleteRefreshToken($tokenHash);

            $newAccessToken  = $this->jwt->generateAccessToken($userId, $user['email']);
            $newRefreshToken = $this->jwt->generateRefreshToken($userId);

            $this->userModel->storeRefreshToken(
                $userId,
                hash('sha256', $newRefreshToken),
                time() + App::REFRESH_TOKEN_TTL,
            );

            $this->setAccessTokenCookie($newAccessToken);
            $this->setRefreshTokenCookie($newRefreshToken);

            $this->jsonSuccess([
                'message'      => 'Token đã được làm mới.',
                'access_token' => $newAccessToken,
            ]);

        } catch (AppException $e) {
            $this->clearTokenCookies();
            $this->jsonException($e);
        }
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    /** @throws AppException */
    private function validateRegistration(string $name, string $email, string $password): void
    {
        if ($name === '') {
            throw AppException::from(ErrorCode::NAME_REQUIRED);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw AppException::from(ErrorCode::EMAIL_INVALID);
        }

        if (strlen($password) < 8) {
            throw AppException::from(ErrorCode::PASSWORD_TOO_SHORT);
        }
    }

    private function setAccessTokenCookie(string $token): void
    {
        setcookie('access_token', $token, [
            'expires'  => time() + App::ACCESS_TOKEN_TTL,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            // 'secure' => true, // uncomment in production (HTTPS)
        ]);
    }

    private function setRefreshTokenCookie(string $token): void
    {
        setcookie('refresh_token', $token, [
            'expires'  => time() + App::REFRESH_TOKEN_TTL,
            'path'     => '/auth',
            'httponly' => true,
            'samesite' => 'Lax',
            // 'secure' => true, // uncomment in production (HTTPS)
        ]);
    }

    private function clearTokenCookies(): void
    {
        setcookie('access_token',  '', ['expires' => time() - 3600, 'path' => '/',             'httponly' => true, 'samesite' => 'Lax']);
        setcookie('refresh_token', '', ['expires' => time() - 3600, 'path' => '/auth', 'httponly' => true, 'samesite' => 'Lax']);
    }
}
