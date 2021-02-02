<?php 

return [
    // 保守陣営 エラーメール含む
    'dev_ops' => array_map('trim', explode(',', env('MAILING_LIST_DEV_OPS', ''))),

    // 担当者　通知する必要がある場合のみ
    'project_manager' => array_map('trim', explode(',', env('MAILING_LIST_PROJECT_MANAGER', ''))),
];