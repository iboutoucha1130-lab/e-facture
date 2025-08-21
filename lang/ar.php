<?php
return [
    'footer' => [
        'copyright' => 'حل الفواتير للمغرب',
        'legal_mentions' => 'إشعارات قانونية',
        'tos' => 'شروط الاستخدام',
        'contact' => 'اتصل بنا'
    ],
    
    'header' => [
        'home' => 'الصفحة الرئيسية',
        'pricing' => 'الأسعار',
        'contact' => 'اتصل بنا',
        'terms' => 'شروط الاستخدام',
        'dashboard' => 'لوحة التحكم',
        'settings' => 'الإعدادات',
        'login' => 'تسجيل الدخول',
        'logout' => 'تسجيل الخروج'
    ],
    'clients' => [
        'title' => 'إدارة العملاء',
        'add_button' => 'إضافة عميل',
        'error_retrieving' => 'خطأ في استرجاع العملاء: ',
        'delete_success' => 'تم حذف العميل بنجاح',
        'delete_error' => 'خطأ أثناء حذف العميل: ',
        'empty_title' => 'لا يوجد عملاء مسجلون',
        'empty_subtitle' => 'ابدأ بإضافة عميلك الأول',
        'table' => [
            'name' => 'الاسم',
            'phone' => 'الهاتف',
            'email' => 'البريد الإلكتروني',
            'ice' => 'رقم التعريف',
            'actions' => 'الإجراءات'
        ],
        'edit_button' => 'تعديل',
        'delete_button' => 'حذف',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذا العميل؟'
    ],
    'cgu' => [
        'title' => 'شروط الاستخدام العامة',
        'page_title' => 'شروط الاستخدام العامة',
        'last_update' => 'آخر تحديث',
    ],
    'contact' => [
        'title' => 'اتصل بنا',
        'contact_us' => 'اتصل بنا',
        'address' => 'العنوان',
        'address_value' => '123 شارع محمد الخامس', 
        'phone' => 'الهاتف',
        'phone_value' => '+212 6 12 34 56 78',
        'email' => 'البريد الإلكتروني',
        'email_value' => 'contact@efacture-maroc.com',
        'follow_us' => 'تابعنا',
        'send_message' => 'أرسل لنا رسالة',
        'form' => [
            'fullname' => 'الاسم الكامل',
            'fullname_placeholder' => 'اسمك الكامل',
            'email' => 'البريد الإلكتروني',
            'email_placeholder' => 'بريدك الإلكتروني',
            'phone' => 'الهاتف',
            'phone_placeholder' => 'رقم هاتفك',
            'subject' => 'الموضوع',
            'subject_options' => [
                'general' => 'استفسار عام',
                'support' => 'الدعم الفني',
                'partnership' => 'شراكة',
                'other' => 'أخرى'
            ],
            'message' => 'الرسالة',
            'message_placeholder' => 'رسالتك...',
            'submit' => 'إرسال الرسالة'
        ]
    ],
    'dashboard' => [
        'title' => 'لوحة التحكم',
        'welcome' => 'مرحبا، :name',
        'documents' => 'المستندات',
        'invoices' => 'الفواتير',
        'quotes' => 'العروض',
        'payments' => 'المدفوعات',
        'contacts' => 'جهات الاتصال',
        'clients' => 'العملاء',
        'prospects' => 'الزبائن المحتملين',
        'responsibles' => 'المسؤولون',
        'management' => 'الإدارة',
        'products' => 'المنتجات',
        'books' => 'الدفاتر',
        'stock' => 'المخزون',
        'revenue_evolution' => 'تطور الإيرادات',
        'revenue_dh' => 'الإيرادات (درهم)',
        'operations' => 'العمليات',
        'total_revenue' => 'إجمالي الإيرادات',
        'avg_operation' => 'متوسط/عملية',
        'period' => 'الفترة',
        'months' => 'أشهر',
        'recent_activity' => 'النشاط الأخير',
        'invoice' => 'فاتورة',
        'quote' => 'عرض',
        'payment' => 'دفع',
        'invoice_date' => 'تاريخ الفاتورة',
        'quote_date' => 'تاريخ العرض',
        'payment_date' => 'تاريخ الدفع',
        'valid_until' => 'صالحة حتى',
        'view' => 'عرض',
        'no_activity' => 'لا يوجد نشاط حديث'
    ],
    'status' => [
        'brouillon' => 'مسودة',
        'envoyee' => 'مرسلة',
        'payee' => 'مدفوعة',
        'impayee' => 'غير مدفوعة',
        'accepte' => 'مقبول',
        'refuse' => 'مرفوض',
        'en-cours' => 'قيد التنفيذ'
    ],
    'devis' => [
        'title' => 'إدارة العروض',
        'add_button' => 'عرض جديد',
        'error_retrieving' => 'خطأ في استرجاع العروض: ',
        'delete_success' => 'تم حذف العرض بنجاح',
        'delete_error' => 'خطأ أثناء الحذف: ',
        'empty_title' => 'ليس لديك أي عروض',
        'empty_subtitle' => 'ابدأ بإنشاء عرضك الأول',
        'table' => [
            'number' => 'رقم العرض',
            'date' => 'التاريخ',
            'client' => 'العميل',
            'amount' => 'المبلغ الإجمالي',
            'status' => 'الحالة',
            'actions' => 'الإجراءات'
        ],
        'status' => [
            'brouillon' => 'مسودة',
            'envoye' => 'مرسل',
            'accepte' => 'مقبول',
            'refuse' => 'مرفوض'
        ],
        'view_button' => 'عرض',
        'edit_button' => 'تعديل',
        'convert_button' => 'تحويل إلى فاتورة',
        'convert_confirm' => 'تحويل هذا العرض إلى فاتورة؟',
        'delete_button' => 'حذف',
        'delete_confirm' => 'هل أنت متأكد من رغبتك في حذف هذا العرض؟'
    ],
    'devis_create' => [
        'title' => 'إنشاء عرض سعر جديد',
        'general_info' => 'معلومات عامة',
        'client_label' => 'العميل',
        'client_required' => 'الرجاء اختيار عميل',
        'creation_date' => 'تاريخ الإنشاء',
        'validity_date' => 'تاريخ الصلاحية',
        'status' => 'الحالة',
        'status_options' => [
            'en-cours' => 'قيد الإجراء',
            'accepte' => 'مقبول',
            'refuse' => 'مرفوض'
        ],
        'vat_rate' => 'معدل الضريبة (%)',
        'lines_title' => 'بنود العرض',
        'lines_error' => 'أضف بندًا واحدًا على الأقل',
        'product_select' => 'اختر منتجًا',
        'description' => 'الوصف',
        'quantity' => 'الكمية',
        'unit_price' => 'سعر الوحدة (درهم)',
        'total' => 'المجموع (درهم)',
        'add_line' => 'إضافة بند',
        'summary' => 'ملخص',
        'subtotal' => 'المجموع قبل الضريبة:',
        'vat_amount' => 'الضريبة ({{rate}}%):',
        'total_ttc' => 'المجموع شامل الضريبة:',
        'cancel' => 'إلغاء',
        'save' => 'حفظ العرض',
        'error_general' => 'خطأ في التحميل:',
        'error_clients' => 'خطأ في تحميل العملاء:',
        'error_products' => 'خطأ في تحميل المنتجات:',
        'error_creation' => 'خطأ في إنشاء العرض:'
    ],
    'devis_edit' => [
        'title' => 'تعديل العرض',
        'edit_quote' => 'تعديل العرض DEV-:id',
        'general_info' => 'المعلومات العامة',
        'client' => 'العميل',
        'select_client' => 'اختر عميلا',
        'creation_date' => 'تاريخ الإنشاء',
        'validity_date' => 'تاريخ الصلاحية',
        'status' => 'الحالة',
        'status_in_progress' => 'قيد الإنجاز',
        'status_accepted' => 'مقبول',
        'status_refused' => 'مرفوض',
        'vat_rate' => 'معدل الضريبة',
        'quote_lines' => 'بنود العرض',
        'product' => 'المنتج',
        'select_product' => 'اختر منتجا',
        'description' => 'الوصف',
        'description_placeholder' => 'الوصف',
        'quantity' => 'الكمية',
        'unit_price' => 'سعر الوحدة',
        'total' => 'المجموع',
        'add_line_button' => 'إضافة بند',
        'total_ht' => 'المجموع بدون ضريبة',
        'vat_amount' => 'الضريبة (:rate%)',
        'total_ttc' => 'المجموع مع الضريبة',
        'cancel_button' => 'إلغاء',
        'update_button' => 'تحديث',
        'error_access' => "خطأ في الوصول إلى العرض: ",
        'error_clients' => "خطأ في تحميل العملاء: ",
        'error_products' => "خطأ في تحميل المنتجات: ",
        'not_found' => "العرض غير موجود",
        'update_error' => "خطأ في تحديث العرض: ",
        'add_line' => "أضف على الأقل بند واحد إلى العرض",
    ],

    'devis_view' => [
        'title' => 'عرض عرض السعر DEV-:id - efacture-maroc.com',
        'not_found' => 'العرض غير موجود أو الوصول مرفوض',
        'back_button' => 'العودة إلى عروض الأسعار',
        'download_pdf' => 'تحميل PDF',
        'edit_button' => 'تعديل',
        'delete_button' => 'حذف',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذا العرض؟',
        'company_ice' => 'رقم التعريف الضريبي',
        'company_address' => 'العنوان',
        'company_phone' => 'الهاتف',
        'company_email' => 'البريد الإلكتروني',
        'document_title' => 'عرض السعر',
        'document_number' => 'رقم',
        'date' => 'التاريخ',
        'status' => 'الحالة',
        'recipient' => 'المستلم',
        'ice' => 'رقم التعريف الضريبي',
        'address' => 'العنوان',
        'phone' => 'الهاتف',
        'email' => 'البريد الإلكتروني',
        'table' => [
            'description' => 'الوصف',
            'quantity' => 'الكمية',
            'unit_price' => 'سعر الوحدة',
            'total' => 'المجموع',
        ],
        'total_ht' => 'المجموع بدون الضريبة',
        'vat' => 'الضريبة',
        'total_ttc' => 'المجموع شامل الضريبة',
        'validity_conditions' => 'شروط الصلاحية',
        'validity_date' => 'تاريخ الصلاحية',
        'validity_text' => 'هذا العرض ساري حتى التاريخ المذكور أعلاه',
        'legal_notice' => 'إشعار قانوني',
        'legal_conformity' => 'متوافق مع التشريع المغربي الساري المفعول.',
        'legal_note' => 'هذا العرض ليس فاتورة ولا يشكل التزامًا بالدفع.',
        'date_prefix' => 'في',
        'signature' => 'التوقيع',
        'signature_note' => 'ختم وتوقيع المسؤول',
        'generating_pdf' => 'جاري التوليد...',
    ],
    'facture_create' => [
        'title' => 'إنشاء فاتورة جديدة',
        'error_loading_clients' => 'خطأ في تحميل العملاء: ',
        'error_loading_products' => 'خطأ في تحميل المنتجات: ',
        'select_client' => 'الرجاء اختيار عميل',
        'invoice_date_required' => 'تاريخ الفاتورة مطلوب',
        'due_date_required' => 'تاريخ الاستحقاق مطلوب',
        'add_at_least_one_line' => 'أضف سطرًا واحدًا على الأقل إلى الفاتورة',
        'invoice_creation_error' => 'خطأ في إنشاء الفاتورة: ',
        'general_info' => 'معلومات عامة',
        'client' => 'العميل',
        'select_client' => 'اختر عميلاً',
        'invoice_date' => 'تاريخ الفاتورة',
        'due_date' => 'تاريخ الاستحقاق',
        'status' => 'الحالة',
        'status_draft' => 'مسودة',
        'status_sent' => 'مرسلة',
        'status_paid' => 'مدفوعة',
        'status_unpaid' => 'غير مدفوعة',
        'vat_rate' => 'معدل الضريبة',
        'billing_lines' => 'بنود الفاتورة',
        'product' => 'المنتج',
        'select_product' => 'اختر منتجاً',
        'description' => 'الوصف',
        'description_placeholder' => 'وصف المنتج/الخدمة',
        'quantity' => 'الكمية',
        'unit_price' => 'سعر الوحدة',
        'total' => 'المجموع',
        'add_line' => 'إضافة سطر',
        'total_ht' => 'الإجمالي قبل الضريبة',
        'vat' => 'الضريبة',
        'total_ttc' => 'الإجمالي شامل الضريبة',
        'cancel' => 'إلغاء',
        'save_invoice' => 'حفظ الفاتورة'
    ],
    'facture_edit' => [
        'title' => 'تعديل الفاتورة FAC-:id',
        'edit_title' => 'تعديل الفاتورة FAC-:id',
        'error_access' => 'خطأ في الوصول إلى الفاتورة: ',
        'error_clients' => 'خطأ في تحميل العملاء: ',
        'error_products' => 'خطأ في تحميل المنتجات: ',
        'not_found' => 'الفاتورة غير موجودة',
        'update_error' => 'خطأ في تحديث الفاتورة: ',
        'select_client' => 'الرجاء تحديد عميل',
        'required_date' => 'تاريخ الفاتورة مطلوب',
        'required_due_date' => 'تاريخ الاستحقاق مطلوب',
        'add_line' => 'أضف سطرًا واحدًا على الأقل إلى الفاتورة',
        'general_info' => 'معلومات عامة',
        'client' => 'العميل',
        'select_client' => 'اختر عميلا',
        'invoice_date' => 'تاريخ الفاتورة',
        'due_date' => 'تاريخ الاستحقاق',
        'status' => 'الحالة',
        'status_draft' => 'مسودة',
        'status_sent' => 'مرسلة',
        'status_paid' => 'مدفوعة',
        'status_unpaid' => 'غير مدفوعة',
        'vat_rate' => 'معدل الضريبة',
        'invoice_lines' => 'بنود الفاتورة',
        'add_line_button' => 'إضافة بند',
        'table' => [
            'product' => 'المنتج',
            'description' => 'الوصف',
            'quantity' => 'الكمية',
            'unit_price' => 'سعر الوحدة',
            'total' => 'المجموع',
        ],
        'select_product' => 'اختر منتجا',
        'description_placeholder' => 'الوصف',
        'total_ht' => 'المجموع بدون ضريبة',
        'vat_amount' => 'الضريبة (:rate%)',
        'total_ttc' => 'المجموع شامل الضريبة',
        'cancel_button' => 'إلغاء',
        'update_button' => 'تحديث',
    ],
    'facture_view' => [
        'title' => 'فاتورة FAC-:id - efacture-maroc.com',
        'error_not_found' => 'الفاتورة غير موجودة أو الوصول مرفوض',
        'back_button' => 'العودة إلى الفواتير',
        'download_pdf' => 'تحميل PDF',
        'edit_button' => 'تعديل',
        'delete_button' => 'حذف',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذه الفاتورة؟',
        'invoice' => 'فاتورة',
        'number' => 'رقم',
        'date' => 'التاريخ',
        'billed_to' => 'فاتورة موجهة إلى:',
        'ice' => 'رقم التعريف الضريبي',
        'address' => 'العنوان',
        'phone' => 'الهاتف',
        'email' => 'البريد الإلكتروني',
        'table' => [
            'description' => 'الوصف',
            'quantity' => 'الكمية',
            'unit_price' => 'سعر الوحدة (درهم)',
            'total' => 'المجموع (درهم)',
        ],
        'total_ht' => 'المجموع قبل الضريبة',
        'vat' => 'الضريبة',
        'total_ttc' => 'المجموع شامل الضريبة',
        'payment_terms' => 'شروط الدفع',
        'due_date' => 'تاريخ الاستحقاق',
        'payment_method' => 'طريقة الدفع',
        'bank_transfer' => 'حوالة بنكية',
        'rib' => 'الرقم البنكي',
        'legal_notice' => 'ملاحظات قانونية',
        'legal_text' => 'مطابق للتشريع المغربي الساري المفعول.',
        'payment_conditions' => 'الفاتورة قابلة للدفع عند الاستلام. أي تأخير في الدفع سيؤدي إلى فرض غرامات تأخير حسب السعر القانوني.',
        'signature' => 'التوقيع',
        'stamp_and_signature' => 'ختم وتوقيع المسؤول',
        'company' => [
            'ice' => 'رقم التعريف الضريبي',
            'address' => 'العنوان',
            'phone' => 'الهاتف',
            'email' => 'البريد الإلكتروني',
        ],
        'pdf_filename' => 'فاتورة-:id.pdf',
        'generating_pdf' => 'جاري إنشاء PDF...',
    ],
    'factures' => [
        'title' => 'إدارة الفواتير',
        'new_button' => 'فاتورة جديدة',
        'error_retrieving' => 'خطأ في استرجاع الفواتير: ',
        'delete_success' => 'تم حذف الفاتورة بنجاح',
        'delete_error' => 'خطأ أثناء الحذف: ',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذه الفاتورة؟',
        
        'filter' => [
            'status' => 'الحالة',
            'all' => 'الكل',
            'client' => 'العميل',
            'all_clients' => 'جميع العملاء',
            'from' => 'من',
            'to' => 'إلى',
            'button' => 'تصفية'
        ],
        
        'status' => [
            'draft' => 'مسودة',
            'sent' => 'مرسلة',
            'paid' => 'مدفوعة',
            'unpaid' => 'غير مدفوعة',
            'brouillon' => 'مسودة',
            'envoyee' => 'مرسلة',
            'payee' => 'مدفوعة',
            'impayee' => 'غير مدفوعة'
        ],
        
        'empty_title' => 'ليس لديك أي فواتير',
        'empty_subtitle' => 'ابدأ بإنشاء فاتورتك الأولى',
        
        'table' => [
            'invoice_number' => 'رقم الفاتورة',
            'date' => 'التاريخ',
            'client' => 'العميل',
            'amount' => 'المبلغ شامل الضريبة',
            'status' => 'الحالة',
            'due_date' => 'تاريخ الاستحقاق',
            'actions' => 'إجراءات'
        ],
        
        'action' => [
            'view' => 'عرض',
            'edit' => 'تعديل',
            'delete' => 'حذف'
        ]
    ],
    'facturer' => [
        'invalid_quote' => 'عرض سعر غير صالح',
        'quote_not_found' => 'عرض السعر غير موجود',
        'conversion_success' => 'تم تحويل عرض السعر إلى فاتورة بنجاح',
        'conversion_error' => 'حدث خطأ أثناء تحويل عرض السعر: :error',
    ],
    'forgot_password' => [
        'title' => 'إعادة تعيين كلمة المرور - efacture-maroc.com',
        'step1' => [
            'title' => 'إعادة تعيين كلمة المرور',
            'subtitle' => 'أدخل معلوماتك للتحقق من هويتك'
        ],
        'step2' => [
            'title' => 'كلمة مرور جديدة',
            'subtitle' => 'حدد كلمة المرور الجديدة الخاصة بك'
        ],
        'step3' => [
            'title' => 'تم إعادة تعيين كلمة المرور',
            'subtitle' => 'يمكنك الآن تسجيل الدخول باستخدام كلمة المرور الجديدة الخاصة بك.'
        ],
        'email' => 'البريد الإلكتروني',
        'fullname' => 'الاسم الكامل',
        'company' => 'اسم الشركة',
        'new_password' => 'كلمة مرور جديدة',
        'confirm_password' => 'تأكيد كلمة المرور',
        'verify_button' => 'التحقق من الهوية',
        'reset_button' => 'إعادة تعيين كلمة المرور',
        'login_button' => 'تسجيل الدخول',
        'placeholder' => [
            'email' => 'example@email.com',
            'fullname' => 'اسمك الكامل',
            'company' => 'اسم شركتك',
            'new_password' => 'كلمة المرور الجديدة الخاصة بك',
            'confirm_password' => 'تأكيد كلمة المرور الخاصة بك'
        ],
        'error' => [
            'required_fields' => 'جميع الحقول مطلوبة',
            'no_account' => 'المعلومات المقدمة لا تتطابق مع أي حساب',
            'password_mismatch' => 'كلمات المرور غير متطابقة',
            'technical' => 'خطأ تقني. يرجى المحاولة مرة أخرى.'
        ],
        'success' => [
            'reset_success' => 'تم إعادة تعيين كلمة المرور الخاصة بك بنجاح'
        ]
    ],
    'index' => [
        'title' => 'efacture-maroc.com - الفواتير الإلكترونية للمغرب',
        'hero' => [
            'title' => 'قم بإدارة فواتيرك بسهولة',
            'subtitle' => 'حل الفواتير الإلكترونية 100% مغربي للشركات الصغيرة والمتوسطة',
            'login_button' => 'تسجيل الدخول',
            'register_button' => 'التسجيل المجاني',
            'dashboard_button' => 'الوصول إلى مساحتي'
        ],
        'features' => [
            'title' => 'لماذا تختار efacture-maroc؟',
            'item1' => [
                'title' => 'متوافق مع القانون المغربي',
                'description' => 'فواتير متوافقة مع معايير المديرية العامة للضرائب مع ICE إلزامي'
            ],
            'item2' => [
                'title' => 'متعدد اللغات',
                'description' => 'واجهة باللغتين الفرنسية والعربية'
            ],
            'item3' => [
                'title' => 'المدفوعات عبر الإنترنت',
                'description' => 'تكامل مع CMI والبنوك المحلية'
            ]
        ],
        'testimonials' => [
            'title' => 'يثقون بنا',
            'quote' => "ساعدني efacture-maroc في توفير وقت ثمين في إدارة متجري الصغير.",
            'author' => 'أحمد، مدير متجر'
        ]
    ],
    'livre_detail' => [
        'title' => 'تفاصيل الدفتر المحاسبي',
        'back_button' => 'رجوع',
        'no_transactions' => 'لم يتم العثور على أي معاملات لهذه الفترة',
        'na' => 'غير متاح',
        'currency' => 'درهم',
        'table' => [
            'date' => 'التاريخ',
            'type' => 'النوع',
            'reference' => 'المرجع',
            'client' => 'العميل',
            'amount' => 'المبلغ',
            'status' => 'الحالة'
        ],
        'types' => [
            'facture' => 'فاتورة',
            'devis' => 'عرض سعر',
            'paiement' => 'دفع'
        ],
        'status' => [
            'brouillon' => 'مسودة',
            'envoye' => 'مرسل',
            'accepte' => 'مقبول',
            'refuse' => 'مرفوض',
            'envoyee' => 'مرسلة',
            'payee' => 'مدفوعة',
            'impayee' => 'غير مدفوعة'
        ],
        'months' => [
            'january' => 'يناير',
            'february' => 'فبراير',
            'march' => 'مارس',
            'april' => 'أبريل',
            'may' => 'ماي',
            'june' => 'يونيو',
            'july' => 'يوليوز',
            'august' => 'غشت',
            'september' => 'شتنبر',
            'october' => 'أكتوبر',
            'november' => 'نونبر',
            'december' => 'دجنبر'
        ]
    ],
    'error' => [
        'db_error' => 'خطأ: '
    ],
    'livres_comptables' => [
        'title' => 'الدفاتر المحاسبية',
        'subtitle' => 'سجل تلقائي لجميع معاملاتك',
        'table' => [
            'period' => 'الفترة',
            'invoices' => 'الفواتير',
            'quotes' => 'العروض',
            'payments' => 'المدفوعات',
            'total_amount' => 'المبلغ الإجمالي',
            'actions' => 'الإجراءات',
            'view_details' => 'عرض التفاصيل'
        ],
        'empty_message' => 'لا توجد معاملات مسجلة',
        'empty_submessage' => 'ستظهر معاملاتك هنا تلقائيا'
    ],
    'error' => [
        'db_error' => 'خطأ: '
    ],
    'login' => [
        'title' => 'تسجيل الدخول - efacture-maroc.com',
        'heading' => 'تسجيل الدخول إلى حسابك',
        'subheading' => 'أدخل بيانات الاعتماد الخاصة بك للوصول إلى مساحتك',
        'email_label' => 'البريد الإلكتروني',
        'email_placeholder' => 'example@email.com',
        'password_label' => 'كلمة المرور',
        'password_placeholder' => 'كلمة المرور الخاصة بك',
        'remember_me' => 'تذكرني',
        'forgot_password' => 'نسيت كلمة المرور؟',
        'submit_button' => 'تسجيل الدخول',
        'no_account' => 'ليس لديك حساب؟',
        'register_link' => 'إنشاء حساب',
        'required_fields' => 'جميع الحقول مطلوبة',
        'invalid_credentials' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        'technical_error' => 'خطأ تقني. يرجى المحاولة مرة أخرى.',
        'logout_success' => 'لقد تم تسجيل خروجك بنجاح'
    ],

    'logout' => [
        'success' => 'تم تسجيل الخروج بنجاح',
        'error' => 'خطأ أثناء تسجيل الخروج'
    ],
    'error' => [
        'token_deletion' => 'خطأ أثناء حذف تذكرني: ',
        'db_error' => 'خطأ في قاعدة البيانات: '
    ],

    'mentions-legales' => [
        'page_title' => "إشعار قانوني - efacture-maroc.com",
        'title' => "إشعار قانوني",
        'intro' => "وفقًا للتشريعات الجاري بها العمل في المغرب، نعلمكم بالإشعار القانوني المتعلق بخدمتنا efacture-maroc.com.",
        
        'section1' => [
            'title' => "1. ناشر الموقع",
            'denomination' => "التسمية الاجتماعية:",
            'denomination_value' => "شركة ذات مسؤولية محدودة efacture-maroc",
            'forme_juridique' => "الشكل القانوني:",
            'forme_juridique_value' => "شركة ذات مسؤولية محدودة",
            'siege_social' => "المقر الاجتماعي:",
            'siege_social_value' => "",
            'telephone' => "الهاتف:",
            'telephone_value' => "+212 5 37 77 77 77",
            'email' => "البريد الإلكتروني:",
            'ice' => "رقم التعريف الموحد للمقاولة:",
            'registre_commerce' => "السجل التجاري:",
            'patente' => "الرخصة:",
            'cnss' => "الصندوق الوطني للضمان الاجتماعي:"
        ],
        
        'section2' => [
            'title' => "2. الاستضافة",
            'content' => "يتم استضافة موقع efacture-maroc.com من قبل:",
            'hebergeur' => "Maroc Telecom Hosting",
            'adresse' => "",
            'telephone' => "الهاتف: +212 5 22 22 22 22"
        ],
        
        'section3' => [
            'title' => "3. الملكية الفكرية",
            'content' => "جميع العناصر المكونة لموقع efacture-maroc.com (نصوص، صور، مقاطع فيديو، شعارات، إلخ) هي ملكية حصرية لشركة ذات مسؤولية محدودة efacture-maroc أو شركائها ومحمية بموجب القوانين المغربية والدولية المتعلقة بالملكية الفكرية."
        ],
        
        'section4' => [
            'title' => "4. حماية البيانات الشخصية",
            'content' => "وفقًا للقانون 09-08 المتعلق بحماية الأشخاص الذاتيين تجاه معالجة البيانات ذات الطابع الشخصي، لديك حق الوصول والتصحيح والاعتراض على البيانات المتعلقة بك.",
            'contact' => "لممارسة هذا الحق، يمكنك الاتصال بنا على عنوان البريد الإلكتروني:"
        ],
        
        'section5' => [
            'title' => "5. ملفات تعريف الارتباط",
            'content' => "يستخدم موقع efacture-maroc.com ملفات تعريف الارتباط لتحسين تجربة المستخدم. لا تحتوي هذه الملفات على أي معلومات شخصية وتستخدم فقط للعمل الفني للموقع."
        ],
        
        'section6' => [
            'title' => "6. المسؤولية",
            'content' => "لا يمكن تحميل شركة ذات مسؤولية محدودة efacture-maroc المسؤولية عن الأضرار المباشرة أو غير المباشرة الناتجة عن استخدام الموقع أو الخدمات المقدمة."
        ],
        
        'section7' => [
            'title' => "7. القانون الواجب التطبيق",
            'content' => "يخضع هذا الإشعار القانوني للقانون المغربي. أي نزاع يتعلق بتفسيره أو تنفيذه يخضع للاختصاص الحصري للمحاكم المغربية."
        ]
    ],
    'paiement_create' => [
        'title' => 'تسجيل دفعة',
        'error_loading' => 'خطأ في تحميل الفواتير: ',
        'select_invoice' => 'الرجاء اختيار فاتورة',
        'amount_error' => 'يجب أن يكون المبلغ أكبر من 0',
        'date_required' => 'تاريخ الدفع مطلوب',
        'save_error' => 'خطأ في الحفظ: ',
        'general_info' => 'المعلومات العامة',
        'invoice' => 'الفاتورة',
        'amount' => 'المبلغ',
        'payment_method' => 'طريقة الدفع',
        'payment_date' => 'تاريخ الدفع',
        'reference' => 'المرجع',
        'notes' => 'ملاحظات',
        'methods' => [
            'bank_transfer' => 'حوالة بنكية',
            'check' => 'شيك',
            'cash' => 'نقدا',
            'card' => 'بطاقة بنكية'
        ],
        'reference_placeholder' => 'رقم الشيك، مرجع الحوالة...',
        'cancel' => 'إلغاء',
        'save' => 'حفظ'
    ],
    'paiement_edit' => [
        'title' => 'تعديل الدفع',
        'back_link' => 'العودة إلى القائمة',
        'error_retrieving' => 'خطأ في استرجاع بيانات الدفع: ',
        'update_success' => 'تم تحديث الدفع بنجاح',
        'update_error' => 'خطأ أثناء التحديث: ',
        'error_factures' => 'خطأ في استرجاع الفواتير: ',
        'invoice_label' => 'الفاتورة المرتبطة',
        'amount_label' => 'المبلغ (درهم)',
        'date_label' => 'تاريخ الدفع',
        'method_label' => 'طريقة الدفع',
        'method_cash' => 'نقداً',
        'method_check' => 'شيك',
        'method_transfer' => 'تحويل بنكي',
        'method_card' => 'بطاقة بنكية',
        'method_other' => 'أخرى',
        'reference_label' => 'المرجع',
        'save_button' => 'حفظ',
    ],
    
    'paiement_view' => [
        'title' => 'دفع PAY-:id - efacture-maroc.com',
        'not_found' => 'الدفع غير موجود أو الوصول مرفوض',
        'back_button' => 'العودة إلى المدفوعات',
        'download_pdf' => 'تحميل PDF',
        'edit_button' => 'تعديل',
        'delete_button' => 'حذف',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذا الدفع؟',
        'company' => [
            'ice' => 'رقم التعريف',
            'address' => 'العنوان',
            'phone' => 'الهاتف',
            'email' => 'البريد الإلكتروني',
        ],
        'receipt_title' => 'إيصال الدفع',
        'receipt_number' => 'رقم PAY-:id',
        'date' => 'التاريخ',
        'received_from' => 'تم الاستلام من:',
        'client' => [
            'ice' => 'رقم التعريف',
            'address' => 'العنوان',
        ],
        'invoice_concerned' => 'الفاتورة المعنية',
        'amount_paid' => 'المبلغ المدفوع',
        'payment_method' => 'طريقة الدفع',
        'reference' => 'المرجع',
        'notes' => 'ملاحظات',
        'no_notes' => 'لا توجد ملاحظات',
        'total_amount' => 'المبلغ الإجمالي',
        'bank_details' => 'تفاصيل البنك',
        'bank' => 'البنك',
        'rib' => 'RIB',
        'swift_code' => 'رمز SWIFT',
        'legal_mentions' => [
            'title' => 'إشعارات قانونية',
            'content' => 'هذا الإيصال يشهد بدفع الفاتورة المذكورة أعلاه.',
        ],
        'date_signed' => 'بتاريخ :date',
        'signature' => 'التوقيع',
        'signature_note' => 'ختم وتوقيع المسؤول',
        'generating_pdf' => 'جاري إنشاء PDF...',
    ],
    'paiements' => [
        'title' => 'إدارة المدفوعات',
        'add_button' => 'تسجيل دفعة',
        'error_retrieving' => 'خطأ في استرجاع المدفوعات: ',
        'delete_success' => 'تم حذف الدفعة بنجاح',
        'delete_error' => 'خطأ أثناء الحذف: ',
        'empty_title' => 'لا توجد مدفوعات مسجلة',
        'empty_subtitle' => 'ابدأ بتسجيل دفعتك الأولى',
        'table' => [
            'date' => 'التاريخ',
            'invoice' => 'رقم الفاتورة',
            'client' => 'العميل',
            'amount' => 'المبلغ',
            'method' => 'طريقة الدفع',
            'reference' => 'المرجع',
            'actions' => 'الإجراءات'
        ],
        'view_button' => 'عرض',
        'edit_button' => 'تعديل',
        'delete_button' => 'حذف',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذه الدفعة؟'
    ],
    'settings' => [
        'title' => 'الإعدادات',
        'tabs' => [
            'general' => 'عام',
            'profile' => 'الملف الشخصي',
            'about' => 'حول'
        ]
    ],
    'language' => [
        'title' => 'اللغة',
        'options' => [
            'fr' => 'الفرنسية',
            'ar' => 'العربية'
        ],
        'button' => 'تغيير اللغة'
    ],
    'notifications' => [
        'title' => 'الإشعارات',
        'email' => 'إشعارات البريد الإلكتروني',
        'deadlines' => 'تنبيهات المواعيد النهائية',
        'payments' => 'تنبيهات المدفوعات'
    ],
    'profile' => [
        'title' => 'الملف الشخصي للمستخدم',
        'subtitle' => 'إدارة المعلومات الشخصية',
        'fullname' => 'الاسم الكامل',
        'email' => 'عنوان البريد الإلكتروني',
        'phone' => 'الهاتف',
        'company' => 'الشركة',
        'update_button' => 'تحديث'
    ],
    'password' => [
        'title' => 'كلمة المرور',
        'subtitle' => 'قم بتغيير كلمة المرور الخاصة بك بانتظام',
        'current' => 'كلمة المرور الحالية',
        'new' => 'كلمة المرور الجديدة',
        'confirm' => 'تأكيد كلمة المرور',
        'update_button' => 'تغيير كلمة المرور'
    ],
    'about' => [
        'title' => 'حول efacture-maroc.com',
        'subtitle' => 'حل الفواتير عبر الإنترنت للمحترفين المغاربة',
        'version' => 'الإصدار',
        'support' => 'الدعم الفني'
    ],
    'error' => [
        'required_field' => 'هذا الحقل مطلوب',
        'database' => 'خطأ في قاعدة البيانات',
        'password_mismatch' => 'كلمات المرور غير متطابقة',
        'wrong_password' => 'كلمة المرور الحالية غير صحيحة'
    ],
    'success' => [
        'profile_update' => 'تم تحديث الملف الشخصي بنجاح',
        'password_update' => 'تم تغيير كلمة المرور بنجاح'
    ],
    'process_contact' => [
        'title' => 'معالجة الاتصال - efacture-maroc.com',
        
        'success' => [
            'title' => 'تم إرسال الرسالة!',
            'message' => 'لقد استلمنا رسالتك وسنرد عليك في أقرب وقت ممكن.',
            'thank_you' => 'شكرا لرسالتك',
            'follow_up' => 'سيتصل بك فريقنا قريبا.',
            'back_button' => 'العودة إلى صفحة الاتصال'
        ],
        
        'errors' => [
            'title' => 'خطأ!',
            'subtitle' => 'يرجى تصحيح الأخطاء أدناه.',
            'description' => 'تعذر إرسال رسالتك بسبب الأخطاء المذكورة أعلاه.',
            'back_button' => 'العودة إلى النموذج',
            
            'name_required' => 'الاسم الكامل مطلوب',
            'email_required' => 'البريد الإلكتروني مطلوب',
            'email_invalid' => 'البريد الإلكتروني غير صالح',
            'message_required' => 'الرسالة مطلوبة'
        ],
        
        'processing' => [
            'title' => 'معالجة رسالتك',
            'message' => 'جاري المعالجة...',
            'subtitle' => 'يرجى الانتظار أثناء معالجة طلبك.'
        ]
    ],
    
    
    'produit_create' => [
        'title' => 'منتج جديد - efacture-maroc.com',
        'heading' => 'إضافة منتج/خدمة جديدة',
        'back_link' => 'العودة إلى القائمة',
        'success_message' => 'تم إنشاء المنتج بنجاح!',
        'view_list' => 'عرض القائمة',
        'errors' => [
            'name_required' => 'اسم المنتج مطلوب',
            'price_invalid' => 'يجب أن يكون السعر رقمًا صالحًا أكبر من 0',
            'image_upload' => 'خطأ في تحميل الصورة',
            'image_type' => 'نوع الملف غير مسموح به. التنسيقات المقبولة: JPG, PNG, GIF',
            'db_error' => 'خطأ في إنشاء المنتج: '
        ],
        'categories' => [
            'service' => 'خدمة',
            'software' => 'برنامج',
            'hardware' => 'عتاد',
            'consultation' => 'استشارة',
            'training' => 'تدريب',
            'maintenance' => 'صيانة',
            'other' => 'أخرى'
        ],
        'form' => [
            'name_label' => 'اسم المنتج',
            'price_label' => 'السعر (درهم)',
            'category_label' => 'الفئة',
            'category_select' => 'اختر فئة',
            'image_label' => 'صورة المنتج',
            'image_hint' => 'التنسيقات المقبولة: JPG, PNG, GIF (الحد الأقصى 2MB)',
            'description_label' => 'الوصف',
            'current_image' => 'الصورة الحالية:',
            'image_alt' => 'صورة المنتج'
        ],
        'cancel_button' => 'إلغاء',
        'save_button' => 'حفظ'
    ],
    'produit_edit' => [
        'title' => 'تعديل المنتج',
        'edit_product' => 'تعديل المنتج: :name',
        'back_to_list' => 'العودة إلى القائمة',
        'success_message' => 'تم تحديث المنتج بنجاح!',
        'view_list' => 'عرض القائمة',
        'error_retrieving' => 'خطأ في استرجاع المنتج: ',
        'update_error' => 'خطأ في تحديث المنتج: ',
        'name_required' => 'اسم المنتج مطلوب',
        'price_invalid' => 'يجب أن يكون السعر رقمًا صالحًا أكبر من 0',
        'upload_error' => 'حدث خطأ أثناء تحميل الصورة',
        'file_type_error' => 'نوع الملف غير مسموح به. الصيغ المقبولة: JPG, PNG, GIF',
        'name_label' => 'اسم المنتج',
        'price_label' => 'السعر (درهم)',
        'category_label' => 'الفئة',
        'select_category' => 'اختر فئة',
        'new_image_label' => 'صورة جديدة',
        'image_keep_note' => 'اتركه فارغًا للاحتفاظ بالصورة الحالية',
        'description_label' => 'الوصف',
        'current_image' => 'الصورة الحالية:',
        'delete_image' => 'حذف هذه الصورة',
        'cancel_button' => 'إلغاء',
        'update_button' => 'تحديث'
    ],
    'produits' => [
        'title' => 'إدارة المنتجات',
        'add_button' => 'إضافة منتج',
        'error_retrieving' => 'خطأ في استرجاع المنتجات: ',
        'delete_success' => 'تم حذف المنتج بنجاح',
        'delete_error' => 'خطأ أثناء الحذف: ',
        'empty_title' => 'ليس لديك أي منتجات مسجلة',
        'empty_subtitle' => 'ابدأ بإضافة منتجك الأول',
        'table' => [
            'name' => 'الاسم',
            'description' => 'الوصف',
            'category' => 'الفئة',
            'price' => 'السعر (درهم)',
            'actions' => 'الإجراءات'
        ],
        'edit_button' => 'تعديل',
        'delete_button' => 'حذف',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذا المنتج؟'
    ],
    'prospects' => [
        'title' => 'إدارة العملاء المحتملين',
        'add_button' => 'إضافة عميل محتمل',
        'error_retrieving' => 'خطأ في استرجاع العملاء المحتملين: ',
        'delete_success' => 'تم حذف العميل المحتمل بنجاح',
        'delete_error' => 'خطأ أثناء الحذف: ',
        'empty_title' => 'ليس لديك أي عملاء محتملين مسجلين',
        'empty_subtitle' => 'ابدأ بإضافة أول عميل محتمل لك',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذا العميل المحتمل؟',
        'edit_button' => 'تعديل',
        'delete_button' => 'حذف',
        'table' => [
            'name' => 'الاسم',
            'company' => 'الشركة',
            'phone' => 'الهاتف',
            'email' => 'البريد الإلكتروني',
            'status' => 'الحالة',
            'actions' => 'الإجراءات'
        ],
        'status' => [
            'nouveau' => 'جديد',
            'contacte' => 'تم الاتصال',
            'suivi' => 'متابعة',
            'converti' => 'تم التحويل'
        ]
    ],
    'prospects_create' => [
        'title' => 'إضافة عميل محتمل',
        'info_section' => 'معلومات العميل المحتمل',
        'fullname_label' => 'الاسم الكامل',
        'fullname_placeholder' => 'الاسم الكامل للعميل المحتمل',
        'company_label' => 'الشركة',
        'company_placeholder' => 'اسم الشركة',
        'phone_label' => 'الهاتف',
        'phone_placeholder' => 'رقم الهاتف',
        'email_label' => 'البريد الإلكتروني',
        'email_placeholder' => 'عنوان البريد الإلكتروني',
        'source_label' => 'المصدر',
        'source_default' => 'اختر مصدرًا',
        'source_website' => 'موقع الويب',
        'source_social' => 'وسائل التواصل الاجتماعي',
        'source_recommendation' => 'توصية',
        'source_event' => 'معرض/حدث',
        'source_other' => 'أخرى',
        'status_label' => 'الحالة',
        'status_new' => 'جديد',
        'status_contacted' => 'تم الاتصال به',
        'status_followup' => 'متابعة',
        'status_converted' => 'تم تحويله',
        'cancel_button' => 'إلغاء',
        'save_button' => 'حفظ العميل المحتمل',
        'error_name_required' => 'اسم العميل المحتمل مطلوب',
        'error_invalid_email' => 'البريد الإلكتروني غير صالح',
        'error_adding' => 'خطأ أثناء إضافة العميل المحتمل: ',
        'success_added' => 'تمت إضافة العميل المحتمل بنجاح'
    ],
    'prospects_edit' => [
        'title' => 'تعديل العميل المحتمل',
        'info_title' => 'معلومات العميل المحتمل',
        'name_label' => 'الاسم',
        'name_placeholder' => 'الاسم الكامل للعميل المحتمل',
        'company_label' => 'الشركة',
        'company_placeholder' => 'اسم الشركة',
        'phone_label' => 'الهاتف',
        'phone_placeholder' => 'رقم الهاتف',
        'email_label' => 'البريد الإلكتروني',
        'email_placeholder' => 'عنوان البريد الإلكتروني',
        'source_label' => 'المصدر',
        'source_placeholder' => 'كيف وجدت هذا العميل المحتمل',
        'status_label' => 'الحالة',
        'status_new' => 'جديد',
        'status_contacted' => 'تم الاتصال به',
        'status_followup' => 'في المتابعة',
        'status_converted' => 'تم تحويله',
        'cancel_button' => 'إلغاء',
        'save_button' => 'تحديث',
        'errors' => [
            'name_required' => 'الاسم مطلوب',
            'company_required' => 'الشركة مطلوبة',
        ],
        'db_error' => 'خطأ في الوصول إلى العميل المحتمل: ',
        'update_error' => 'خطأ أثناء تحديث العميل المحتمل: ',
        'success' => 'تم تحديث العميل المحتمل بنجاح',
    ],
    'register' => [
        'title' => 'تسجيل - efacture-maroc.com',
        'heading' => 'إنشاء حساب',
        'subtitle' => 'انضم إلى منصة الفواتير الإلكترونية لدينا',
        'success_message' => 'تم إنشاء الحساب بنجاح! <a href="login.php" class="font-medium text-green-800 hover:text-green-700">تسجيل الدخول</a>',
        'general_error' => 'خطأ تقني. يرجى المحاولة مرة أخرى.',
        'form' => [
            'name' => 'الاسم الكامل',
            'name_placeholder' => 'اسمك الكامل',
            'name_error' => 'الاسم الكامل مطلوب',
            'email' => 'البريد الإلكتروني',
            'email_placeholder' => 'example@email.com',
            'email_error' => [
                'required' => 'البريد الإلكتروني مطلوب',
                'invalid' => 'صيغة البريد الإلكتروني غير صالحة',
                'used' => 'هذا البريد الإلكتروني مستخدم بالفعل'
            ],
            'company' => 'اسم الشركة',
            'company_placeholder' => 'شركتك (اختياري)',
            'password' => 'كلمة المرور',
            'password_placeholder' => 'كلمة المرور الخاصة بك',
            'password_error' => [
                'required' => 'كلمة المرور مطلوبة',
                'length' => 'يجب أن تحتوي كلمة المرور على 6 أحرف على الأقل'
            ],
            'confirm_password' => 'تأكيد كلمة المرور',
            'confirm_password_placeholder' => 'تأكيد كلمة المرور الخاصة بك',
            'confirm_password_error' => 'كلمات المرور غير متطابقة',
            'submit' => 'تسجيل',
            'login_link' => 'لديك حساب بالفعل؟ <a href="login.php" class="font-medium text-primary hover:text-green-800">تسجيل الدخول</a>',
            'required_fields' => '* الحقول المطلوبة'
        ],
        'account_creation_error' => 'خطأ في إنشاء الحساب: '
    ],

    'responsable_create' => [
        'title' => 'إضافة مسؤول جديد',
        'personal_info' => 'المعلومات الشخصية',
        'fullname' => 'الاسم الكامل',
        'email' => 'البريد الإلكتروني',
        'role' => 'الدور',
        'status' => 'الحالة',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'permissions_title' => 'الصلاحيات',
        'permissions' => [
            'invoices' => 'إدارة الفواتير',
            'quotes' => 'إدارة العروض',
            'clients' => 'إدارة العملاء',
            'products' => 'إدارة المنتجات',
            'payments' => 'إدارة المدفوعات',
            'reports' => 'عرض التقارير'
        ],
        'cancel' => 'إلغاء',
        'save' => 'حفظ',
        'success' => 'تم إنشاء المسؤول بنجاح',
        'errors' => [
            'name_required' => 'الاسم مطلوب',
            'email_required' => 'البريد الإلكتروني مطلوب',
            'email_invalid' => 'البريد الإلكتروني غير صالح',
            'role_required' => 'الدور مطلوب',
            'email_exists' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'general' => 'حدث خطأ أثناء إنشاء المسؤول: '
        ]
    ],

    'responsable_edit' => [
        'title' => 'Modifier responsable',
        'general_info' => 'Informations générales',
        'fullname' => 'Nom complet',
        'email' => 'Email',
        'role' => 'Rôle',
        'permissions_title' => 'Permissions',
        'permissions' => [
            'invoices' => 'Gestion des factures',
            'quotes' => 'Gestion des devis',
            'clients' => 'Gestion des clients',
            'products' => 'Gestion des produits',
            'payments' => 'Gestion des paiements',
            'reports' => 'Accès aux rapports'
        ],
        'cancel_button' => 'Annuler',
        'update_button' => 'Mettre à jour',
        'update_success' => 'Responsable mis à jour avec succès',
        'db_error' => 'Erreur d\'accès au responsable: ',
        'update_error' => 'Erreur lors de la mise à jour: ',
        'errors' => [
            'name_required' => 'Le nom est obligatoire',
            'email_required' => 'L\'email est obligatoire',
            'email_invalid' => 'L\'email n\'est pas valide',
            'role_required' => 'Le rôle est obligatoire'
        ]
    ],
    'responsables' => [
        'title' => 'إدارة المسؤولين',
        'subtitle' => 'إدارة الوصول والأذونات لفريقك',
        'add_button' => 'إضافة مسؤول',
        'search_placeholder' => 'بحث...',
        'search_button' => 'بحث',
        'count_label' => 'مسؤول(ون)',
        'delete_success' => 'تم حذف المسؤول بنجاح',
        'delete_error' => 'حدث خطأ أثناء الحذف: ',
        'delete_confirm' => 'هل أنت متأكد من رغبتك في حذف هذا المسؤول؟',
        'empty_message' => 'لا يوجد مسؤولين مسجلين',
        'table' => [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'role' => 'الدور',
            'permissions' => 'الأذونات',
            'actions' => 'الإجراءات'
        ]
    ],
    'error' => [
        'db_error' => 'خطأ: '
    ],
    'stock' => [
        'title' => 'إدارة المخزون',
        'subtitle' => 'تابع وقم بإدارة مخزونك',
        'add_button' => 'إضافة إلى المخزون',
        'table' => [
            'product' => 'المنتج',
            'category' => 'الفئة',
            'unit_price' => 'السعر الوحدة',
            'quantity' => 'الكمية',
            'alert_threshold' => 'حد التنبيه',
            'location' => 'الموقع',
            'actions' => 'الإجراءات',
            'empty' => 'لا توجد منتجات في المخزون حاليا',
        ],
        'modal' => [
            'add_title' => 'إضافة إلى المخزون',
            'edit_title' => 'تعديل المخزون',
            'product_label' => 'المنتج',
            'product_select' => 'اختر منتجا',
            'quantity_label' => 'الكمية',
            'threshold_label' => 'حد التنبيه',
            'location_label' => 'الموقع',
            'cancel' => 'إلغاء',
            'save' => 'حفظ',
        ],
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذا العنصر من المخزون؟',
        'alerts' => [
            'critical' => 'نفاذ المخزون',
            'low' => 'مخزون منخفض',
        ],
        'errors' => [
            'db_error' => 'خطأ في قاعدة البيانات: ',
        ],
    ],
    'tarifs' => [
        'title' => 'عروض الاشتراكات',
        'subtitle' => 'اختر الخطة التي تناسب احتياجاتك',
        'popular' => 'الأكثر شعبية',
        
        'basic' => [
            'name' => 'أساسي',
            'price' => 'مجانًا',
            'period' => 'مدى الحياة',
            'button' => 'ابدأ الآن'
        ],
        
        'pro' => [
            'name' => 'احترافي',
            'price' => '199 درهم',
            'period' => 'شهريًا',
            'button' => 'جرب مجانًا'
        ],
        
        'enterprise' => [
            'name' => 'مؤسسة',
            'price' => 'مخصص',
            'period' => 'حل حسب الطلب',
            'button' => 'اتصل بالبيع'
        ],
        
        'features' => [
            'invoices' => 'فواتير غير محدودة',
            'clients' => 'عملاء غير محدودين',
            'support' => 'دعم عبر البريد الإلكتروني',
            'storage' => 'تخزين المستندات',
            'advanced' => 'ميزات متقدمة',
            'team' => 'إدارة الفريق',
            'priority' => 'دعم ذو أولوية',
            'reports' => 'تقارير متقدمة'
        ],
        
        'cta_title' => 'هل لديك احتياجات خاصة؟',
        'cta_subtitle' => 'اتصل بفريقنا لحل مخصص يناسب عملك.',
        'cta_button' => 'طلب عرض سعر'
    ],

    'contact' => [
        'title' => 'اتصل بنا - efacture-maroc.com',
        'contact_us' => 'اتصل بنا',
        'address' => 'العنوان',
        'address_value' => '',
        'phone' => 'الهاتف',
        'phone_value' => '212 6 12 34 56 78+',
        'email' => 'البريد الإلكتروني',
        'email_value' => 'contact@efacture-maroc.com',
        'follow_us' => 'تابعونا',
        'send_message' => 'أرسل لنا رسالة',
        'form' => [
            'fullname' => 'الاسم الكامل',
            'email' => 'البريد الإلكتروني',
            'phone' => 'الهاتف',
            'subject' => 'الموضوع',
            'subject_options' => [
                'general' => 'سؤال عام',
                'support' => 'الدعم الفني',
                'partnership' => 'شراكة',
                'other' => 'أخرى'
            ],
            'message' => 'الرسالة',
            'submit' => 'إرسال الرسالة'
        ]
    ],
    'clients' => [
        'title' => 'إدارة العملاء',
        'add_button' => 'إضافة عميل',
        'empty_title' => 'لا يوجد عملاء مسجلون',
        'empty_subtitle' => 'ابدأ بإضافة عميلك الأول',
        'error_retrieving' => 'خطأ في استرجاع العملاء: ',
        'delete_success' => 'تم حذف العميل بنجاح',
        'delete_error' => 'خطأ أثناء حذف العميل: ',
        'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذا العميل؟',
        'delete_button' => 'حذف',
        'edit_button' => 'تعديل',
        'table' => [
            'name' => 'الاسم',
            'phone' => 'الهاتف',
            'email' => 'البريد الإلكتروني',
            'ice' => 'رقم التعريف',
            'actions' => 'الإجراءات'
        ]
    ],
    'client_edit' => [
        'title' => 'تعديل العميل',
        'success_message' => 'تم تحديث العميل بنجاح.',
        'back_to_list' => 'العودة إلى قائمة العملاء',
        'cancel_button' => 'إلغاء',
        'save_button' => 'حفظ',
        'errors' => [
            'name_required' => 'اسم العميل مطلوب.',
            'ice_required' => 'رقم ICE مطلوب.',
            'ice_invalid' => 'يجب أن يتكون رقم ICE من 15 رقما.',
            'email_invalid' => 'البريد الإلكتروني غير صالح.'
        ],
        'form' => [
            'name_label' => 'الاسم الكامل',
            'name_placeholder' => 'اسم العميل',
            'ice_label' => 'رقم ICE',
            'ice_placeholder' => '15 رقما ل ICE',
            'phone_label' => 'الهاتف',
            'phone_placeholder' => 'رقم الهاتف',
            'email_label' => 'البريد الإلكتروني',
            'email_placeholder' => 'عنوان البريد الإلكتروني',
            'address_label' => 'العنوان',
            'address_placeholder' => 'العنوان الكامل',
            'city_label' => 'المدينة',
            'city_placeholder' => 'اسم المدينة',
            'postal_label' => 'الرمز البريدي',
            'postal_placeholder' => 'الرمز البريدي'
        ]
    ],
    'error' => [
        'db_error' => 'خطأ في قاعدة البيانات : '
    ],
'client_create' => [
    'title' => 'إنشاء عميل جديد',
    'errors' => [
        'name_required' => 'اسم العميل مطلوب',
        'ice_required' => 'رقم ICE مطلوب',
        'ice_invalid' => 'يجب أن يتكون رقم ICE من 15 رقمًا بالضبط',
        'email_invalid' => 'البريد الإلكتروني غير صالح',
        'db_error' => 'خطأ أثناء الحفظ: '
    ],
    'success' => [
        'message' => 'تم إنشاء العميل بنجاح. ',
        'link' => 'عرض قائمة العملاء'
    ],
    'form' => [
        'name' => 'الاسم الكامل',
        'name_placeholder' => 'أدخل الاسم الكامل للعميل',
        'ice' => 'رقم ICE',
        'ice_placeholder' => 'أدخل 15 رقمًا لـ ICE',
        'phone' => 'الهاتف',
        'phone_placeholder' => 'أدخل رقم الهاتف',
        'email' => 'البريد الإلكتروني',
        'email_placeholder' => 'أدخل بريد العميل الإلكتروني',
        'address' => 'العنوان',
        'address_placeholder' => 'أدخل العنوان الكامل',
        'city' => 'المدينة',
        'city_placeholder' => 'أدخل اسم المدينة',
        'zip' => 'الرمز البريدي',
        'zip_placeholder' => 'أدخل الرمز البريدي'
    ],
    'buttons' => [
        'cancel' => 'إلغاء',
        'save' => 'حفظ'
    ]
],
    'cgu' => [
        'title' => 'شروط الاستخدام العامة',
        'page_title' => 'شروط الاستخدام العامة',
        'last_update' => 'آخر تحديث',
        'section1' => [
            'title' => '1. الهدف',
            'content' => 'تهدف شروط الاستخدام العامة هذه (CGU) إلى تحديد شروط تقديم خدمات موقع efacture-maroc.com وشروط استخدام الخدمات من قبل المستخدم.'
        ],
        'section2' => [
            'title' => '2. قبول شروط الاستخدام',
            'item1' => [
                'label' => 'القبول',
                'text' => 'أي استخدام للخدمة يعني القبول الكامل وغير المشروط لشروط الاستخدام هذه.'
            ],
            'item2' => [
                'label' => 'التعديل',
                'text' => 'يمكن تعديل شروط الاستخدام في أي وقت، ويتم تشجيع المستخدمين على مراجعتها بانتظام.'
            ],
            'item3' => [
                'label' => 'الصلاحية',
                'text' => 'تظل شروط الاستخدام سارية طوال مدة استخدام الخدمة.'
            ],
            'item4' => [
                'label' => 'الاختصاص القضائي',
                'text' => 'في حالة النزاع، تكون المحاكم المغربية هي المختصة حصريًا.'
            ]
        ],
        'section3' => [
            'title' => '3. وصف الخدمات',
            'paragraph1' => 'يقدم موقع efacture-maroc.com خدمة الفواتير الإلكترونية المتوافقة مع التشريع المغربي.',
            'paragraph2' => 'تشمل الخدمات إنشاء وإرسال ومتابعة الفواتير الإلكترونية.'
        ],
        'section4' => [
            'title' => '4. التزامات المستخدم',
            'intro' => 'يتعهد المستخدم بما يلي:',
            'item1' => 'تقديم معلومات دقيقة وحديثة',
            'item2' => 'احترام التشريع المغربي الساري',
            'item3' => 'عدم استخدام الخدمة لأغراض غير قانونية',
            'item4' => 'الحفاظ على سرية بيانات الدخول'
        ],
        'section5' => [
            'title' => '5. المسؤوليات',
            'paragraph1' => 'لا يمكن تحميل efacture-maroc.com مسؤولية الأخطاء التي يرتكبها المستخدم عند إدخال المعلومات.',
            'paragraph2' => 'لا يمكن تحميل efacture-maroc.com المسؤولية في حالات القوة القاهرة.'
        ],
        'section6' => [
            'title' => '6. الملكية الفكرية',
            'intro' => 'جميع عناصر الموقع محمية بحقوق المؤلف:',
            'item1' => 'النصوص والصور والرسومات',
            'item2' => 'البرامج وقواعد البيانات',
            'conclusion' => 'يمنع أي نسخ دون إذن.'
        ],
        'section7' => [
            'title' => '7. البيانات الشخصية',
            'paragraph1' => 'يتم معالجة البيانات المجمعة وفقًا للقانون 09-08 المتعلق بحماية الأشخاص الذاتيين تجاه معالجة البيانات الشخصية.',
            'paragraph2' => 'للمستخدم حق الوصول والتصحيح والاعتراض.'
        ],
        'section8' => [
            'title' => '8. الأسعار والدفع',
            'paragraph1' => 'تتوفر أسعار الخدمات على الموقع ويمكن تعديلها في أي وقت.',
            'paragraph2' => 'يتم الدفع عن طريق التحويل البنكي أو بطاقة الائتمان.'
        ],
        'section9' => [
            'title' => '9. الإنهاء',
            'content' => 'يمكن للمستخدم إنهاء حسابه في أي وقت. يصبح الإنهاء ساري المفعول فورًا.'
        ],
        'contact' => [
            'title' => 'اتصل بنا',
            'email' => 'contact@efacture-maroc.com',
            'phone' => '+212 5 22 22 22 22',
            'address' => '123 شارع محمد الخامس، الدار البيضاء، المغرب'
        ]
    ]
];