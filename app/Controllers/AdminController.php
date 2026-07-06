<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware\RoleMiddleware;
use App\Enums\ErrorCode;
use App\Enums\Role;
use App\Exceptions\AppException;
use App\Models\UserModel;

final class AdminController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // -----------------------------------------------------------------------
    // GET /admin/dashboard – render SPA-like customer management page
    // -----------------------------------------------------------------------

    public function dashboard(): void
    {
        $payload = RoleMiddleware::handle(Role::ADMIN);

        $this->view('admin/dashboard', [
            'title'   => 'Quản lý Khách hàng',
            'payload' => $payload,
        ], 'dashboard');
    }

    // -----------------------------------------------------------------------
    // GET /admin/customers – list all customers (JSON)
    // -----------------------------------------------------------------------

    public function listCustomers(): void
    {
        RoleMiddleware::handle(Role::ADMIN);

        $customers = $this->userModel->getAllCustomers();

        $this->jsonSuccess(['customers' => $customers]);
    }

    // -----------------------------------------------------------------------
    // POST /admin/customers – create a new customer
    // -----------------------------------------------------------------------

    public function createCustomer(): void
    {
        try {
            RoleMiddleware::handle(Role::ADMIN);

            $body     = $this->parseBody();
            $name     = trim($body['name']     ?? '');
            $email    = trim($body['email']    ?? '');
            $password = trim($body['password'] ?? '');

            if ($name === '') {
                throw AppException::from(ErrorCode::NAME_REQUIRED);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw AppException::from(ErrorCode::EMAIL_INVALID);
            }
            if (strlen($password) < 8) {
                throw AppException::from(ErrorCode::PASSWORD_TOO_SHORT);
            }
            if ($this->userModel->findByEmail($email) !== null) {
                throw AppException::from(ErrorCode::EMAIL_ALREADY_EXISTS);
            }

            $hash   = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $userId = $this->userModel->create($name, $email, $hash, Role::CUSTOMER);

            $this->jsonSuccess([
                'message'  => 'Tạo khách hàng thành công.',
                'customer' => ['id' => $userId, 'name' => $name, 'email' => $email, 'role' => 'customer'],
            ], 201);

        } catch (AppException $e) {
            $this->jsonException($e);
        }
    }

    // -----------------------------------------------------------------------
    // PUT /admin/customers/{id} – update a customer
    // -----------------------------------------------------------------------

    public function updateCustomer(string $id): void
    {
        try {
            RoleMiddleware::handle(Role::ADMIN);

            $customerId = (int) $id;
            if ($customerId <= 0) {
                throw AppException::from(ErrorCode::USER_NOT_FOUND);
            }

            $body  = $this->parseBody();
            $name  = trim($body['name']  ?? '');
            $email = trim($body['email'] ?? '');
            $pass  = trim($body['password'] ?? '');

            if ($name === '') {
                throw AppException::from(ErrorCode::NAME_REQUIRED);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw AppException::from(ErrorCode::EMAIL_INVALID);
            }
            if ($pass !== '' && strlen($pass) < 8) {
                throw AppException::from(ErrorCode::PASSWORD_TOO_SHORT);
            }

            // Check email uniqueness (allow same email for this user)
            $existing = $this->userModel->findByEmail($email);
            if ($existing !== null && (int) $existing['id'] !== $customerId) {
                throw AppException::from(ErrorCode::EMAIL_ALREADY_EXISTS);
            }

            $hash    = $pass !== '' ? password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]) : null;
            $updated = $this->userModel->updateCustomer($customerId, $name, $email, $hash);

            if (!$updated) {
                throw AppException::from(ErrorCode::USER_NOT_FOUND);
            }

            $this->jsonSuccess(['message' => 'Cập nhật khách hàng thành công.']);

        } catch (AppException $e) {
            $this->jsonException($e);
        }
    }

    // -----------------------------------------------------------------------
    // DELETE /admin/customers/{id} – delete a customer
    // -----------------------------------------------------------------------

    public function deleteCustomer(string $id): void
    {
        try {
            RoleMiddleware::handle(Role::ADMIN);

            $customerId = (int) $id;
            if ($customerId <= 0) {
                throw AppException::from(ErrorCode::USER_NOT_FOUND);
            }

            $deleted = $this->userModel->deleteCustomer($customerId);

            if (!$deleted) {
                throw AppException::from(ErrorCode::USER_NOT_FOUND);
            }

            $this->jsonSuccess(['message' => 'Xóa khách hàng thành công.']);

        } catch (AppException $e) {
            $this->jsonException($e);
        }
    }

    // -----------------------------------------------------------------------
    // Helper: parse request body (JSON or form-encoded)
    // -----------------------------------------------------------------------

    private function parseBody(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            return (array) (json_decode($raw ?: '', true) ?? []);
        }

        // PUT/DELETE via fetch with URLSearchParams sends application/x-www-form-urlencoded
        parse_str(file_get_contents('php://input') ?: '', $parsed);
        return array_merge($_POST, $parsed);
    }
}
