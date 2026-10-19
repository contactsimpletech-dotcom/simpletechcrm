<?php
// Supplier / corporate partner registration — auto-loaded

// Public registration form
Router::get('/admin/supplier/corporate/profile', 'SupplierController@showRegister');
Router::post('/admin/supplier/corporate/profile', 'SupplierController@register');

// Admin: view all applications (auth required — enforced inside the controller)
Router::get('/admin/supplier/applications', 'SupplierController@applications');
Router::post('/admin/supplier/status', 'SupplierController@updateStatus');
