<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/api/dicom/mpps' => [[['_route' => 'api_dicom_mpps', '_controller' => 'App\\Controller\\DicomMppsController::update'], null, ['POST' => 0], null, false, false, null]],
        '/api/dicom/non-reconcilies' => [[['_route' => 'api_dicom_non_reconcilies', '_controller' => 'App\\Controller\\DicomReconciliationController::list'], null, ['GET' => 0], null, false, false, null]],
        '/api/dicom/dicom/reconciliation' => [[['_route' => 'app_dicom_reconciliation', '_controller' => 'App\\Controller\\DicomReconciliationController::index'], null, ['GET' => 0], null, false, false, null]],
        '/api/dicom/webhook' => [[['_route' => 'api_dicom_webhook', '_controller' => 'App\\Controller\\DicomWebhookController::receive'], null, ['POST' => 0], null, false, false, null]],
        '/api/dicom/worklist' => [[['_route' => 'api_dicom_worklist', '_controller' => 'App\\Controller\\DicomWorklistController::worklist'], null, ['GET' => 0], null, false, false, null]],
        '/api/examens' => [
            [['_route' => 'examen_create', '_controller' => 'App\\Controller\\ExamenController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'examen_list', '_controller' => 'App\\Controller\\ExamenController::list'], null, ['GET' => 0], null, false, false, null],
        ],
        '/api/examens/stats/resume' => [[['_route' => 'examen_stats', '_controller' => 'App\\Controller\\ExamenController::stats'], null, ['GET' => 0], null, false, false, null]],
        '/' => [[['_route' => 'home', '_controller' => 'App\\Controller\\ExamenPageController::home'], null, ['GET' => 0], null, false, false, null]],
        '/examens' => [[['_route' => 'examen_page', '_controller' => 'App\\Controller\\ExamenPageController::index'], null, ['GET' => 0], null, false, false, null]],
        '/api/machines' => [[['_route' => 'app_machine_getall', '_controller' => 'App\\Controller\\MachineController::getAll'], null, ['GET' => 0], null, false, false, null]],
        '/api/patients' => [
            [['_route' => 'patient_create', '_controller' => 'App\\Controller\\PatientController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'patient_list', '_controller' => 'App\\Controller\\PatientController::list'], null, ['GET' => 0], null, false, false, null],
        ],
        '/api/auth' => [[['_route' => 'api_auth', '_controller' => 'App\\Controller\\SecurityController::login'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:98)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:134)'
                                .'|router(*:148)'
                                .'|exception(?'
                                    .'|(*:168)'
                                    .'|\\.css(*:181)'
                                .')'
                            .')'
                            .'|(*:191)'
                        .')'
                    .')'
                .')'
                .'|/api/(?'
                    .'|dicom/(?'
                        .'|non\\-reconcilies/([^/]++)/valider(*:252)'
                        .'|dicom/reconcilier/([^/]++)(*:286)'
                    .')'
                    .'|examens/([^/]++)(?'
                        .'|(*:314)'
                        .'|/(?'
                            .'|dicom\\-(?'
                                .'|source(*:342)'
                                .'|file(*:354)'
                            .')'
                            .'|statut(*:369)'
                            .'|recreer(*:384)'
                            .'|worklist(*:400)'
                        .')'
                        .'|(*:409)'
                    .')'
                    .'|machines/([^/]++)(?'
                        .'|(*:438)'
                        .'|/statut(*:453)'
                    .')'
                    .'|patients/([^/]++)(?'
                        .'|(*:482)'
                        .'|/archiver(*:499)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        98 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        134 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        148 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        168 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        181 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        191 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        252 => [[['_route' => 'api_dicom_validate_non_reconcilie', '_controller' => 'App\\Controller\\DicomReconciliationController::validate'], ['id'], ['POST' => 0], null, false, false, null]],
        286 => [[['_route' => 'app_dicom_do_reconcile', '_controller' => 'App\\Controller\\DicomReconciliationController::reconcilier'], ['id'], ['POST' => 0], null, false, true, null]],
        314 => [
            [['_route' => 'examen_show', '_controller' => 'App\\Controller\\ExamenController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'examen_update', '_controller' => 'App\\Controller\\ExamenController::update'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        342 => [[['_route' => 'examen_dicom_source', '_controller' => 'App\\Controller\\ExamenController::dicomSource'], ['id'], ['GET' => 0], null, false, false, null]],
        354 => [[['_route' => 'examen_dicom_file', '_controller' => 'App\\Controller\\ExamenController::dicomFile'], ['id'], ['GET' => 0], null, false, false, null]],
        369 => [[['_route' => 'examen_update_statut', '_controller' => 'App\\Controller\\ExamenController::updateStatut'], ['id'], ['PATCH' => 0], null, false, false, null]],
        384 => [[['_route' => 'examen_recreate', '_controller' => 'App\\Controller\\ExamenController::recreate'], ['id'], ['POST' => 0], null, false, false, null]],
        400 => [[['_route' => 'examen_worklist', '_controller' => 'App\\Controller\\ExamenController::genererWorklist'], ['id'], ['POST' => 0], null, false, false, null]],
        409 => [[['_route' => 'examen_delete', '_controller' => 'App\\Controller\\ExamenController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        438 => [[['_route' => 'app_machine_getmachine', '_controller' => 'App\\Controller\\MachineController::getMachine'], ['id'], ['GET' => 0], null, false, true, null]],
        453 => [[['_route' => 'app_machine_updatestatut', '_controller' => 'App\\Controller\\MachineController::updateStatut'], ['id'], ['PATCH' => 0], null, false, false, null]],
        482 => [
            [['_route' => 'patient_show', '_controller' => 'App\\Controller\\PatientController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'patient_update', '_controller' => 'App\\Controller\\PatientController::update'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        499 => [
            [['_route' => 'patient_archive', '_controller' => 'App\\Controller\\PatientController::archiver'], ['id'], ['PATCH' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
