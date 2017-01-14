<?php
use RedBeanPHP\Facade as RedBean;

class DeleteAllUsersController extends Controller {
    const PATH = '/delete-all-users';

    public function validations() {
        return [
            'permission' => 'staff_3',
            'requestData' => []
        ];
    }

    public function handler() {
        $password = Controller::request('password');

        if(!Hashing::verifyPassword($password, Controller::getLoggedUser()->password)) {
            Response::respondError(ERRORS::INVALID_PASSWORD);
            return;
        }

        Redbean::exec('SET FOREIGN_KEY_CHECKS = 0;');
        RedBean::wipe(SessionCookie::TABLE);
        RedBean::wipe(User::TABLE);
        RedBean::wipe(Ticket::TABLE);
        RedBean::wipe(Ticketevent::TABLE);
        RedBean::wipe('ticket_user');
        Redbean::exec('SET FOREIGN_KEY_CHECKS = 1;');

        Response::respondSuccess();
    }
}