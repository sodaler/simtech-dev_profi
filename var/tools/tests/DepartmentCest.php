<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class DepartmentCest
{
    public function test(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Sign in');
        $I->fillField(['id' => 'login_main_login'] , 'hello123@mail.ru');
        $I->fillField(['id' => 'psw_main_login'] , 'hello123');
        $I->click('Sign in');
        $I->Click('My Account');
        $I->Click('_departments');
        $I->Click('department_1');
        $I->See('Анна Петрова');
        $I->See('Администратор Главный');
        $I->amOnPage('/admin.php');
        $I->fillField(['id' => 'username'] , 'sodaler779@mail.ru');
        $I->fillField(['id' => 'password'] , 'sodaler779');
        $I->click('Sign in');
        $I->Click('Customers');
        $I->Click('Departments');
        $I->see('department_1');
        $I->click('', 'a.btn.cm-tooltip');
        $I->see('name');
        $I->see('Image');
        $I->see('Description');
        $I->see('Status');
        $I->see('Employees');
        $I->see('Director');
        $I->fillField(['id' => 'elm_department_name'], 'example');
        $I->fillField(['id' => 'elm_department_description'], 'example_description');
        $I->click('Create');
        $I->amOnPage('/admin.php?dispatch=profiles.manage_departments');
        $I->see('example');
        $I->click('example');
        $I->click('', 'a.btn.dropdown-toggle');
        $I->click('Delete');
        $I->makeHtmlSnapshot();
    }
}