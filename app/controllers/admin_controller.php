<?php

function admin_page()
{
    require_role('admin');

    view('admin_home', ['title' => 'لوحة التحكم']);
}