<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/

// Include required files
require_once APP_DIR . 'core/ORM.php';
require_once APP_DIR . 'models/User.php';
require_once APP_DIR . 'controllers/AuthController.php';
require_once APP_DIR . 'controllers/StudentController.php';
require_once APP_DIR . 'controllers/CounselorController.php';
require_once APP_DIR . 'middleware/AuthMiddleware.php';
require_once APP_DIR . 'middleware/RoleMiddleware.php';

// Public routes
$router->get('/', 'Welcome::index');
$router->post('/api/register', 'AuthController::register');
$router->post('/api/login', 'AuthController::login');

// Protected routes
$router->post('/api/logout', 'AuthController::logout');

// Student routes
$router->get('/api/student/dashboard', 'StudentController::dashboard');
$router->get('/api/student/profile', 'StudentController::profile');

// Counselor routes
$router->get('/api/counselor/dashboard', 'CounselorController::dashboard');
$router->get('/api/counselor/profile', 'CounselorController::profile');