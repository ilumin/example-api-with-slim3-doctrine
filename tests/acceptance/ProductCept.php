<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('display all products');
$I->amOnPage('/products');
$I->see('[xXx]');
