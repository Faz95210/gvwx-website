<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\rbac\ManagerInterface;

class RbacController extends Controller {

    public static function getUserRoles($userId) {
        $auth = Yii::$app->authManager;
        return $auth->getAssignments($userId);
    }

    public static function addUserRole($userId, $role) {
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole($role), $userId);
    }

    public static function setUserRole($userId, $role) {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($userId);
        $auth->assign($auth->getRole($role), $userId);
    }

    public static function revokeUserRole($userId, $role) {
        $auth = Yii::$app->authManager;
        $auth->revoke($auth->getRole($role), $userId);
    }

    public static function revokeUserRoles($userId) {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($userId);
    }

    public function actionInit() {
        $auth = Yii::$app->authManager;
        $auth->removeAll();


        $this->initCompanyAdminRole($auth);
    }

    private function initCompanyAdminRole(ManagerInterface $auth) {
        $admin = $auth->createPermission('companyAdmin');
        $admin->description = 'Company admin';
        $perm = $auth->getPermission('company');


        $auth->add($admin);
        $role = $auth->createRole('admin');
        $auth->add($role);
        $auth->addChild($role, $perm);
        $auth->addChild($role, $admin);
    }
}
