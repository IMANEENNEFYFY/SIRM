<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/dicom/mpps' => [[['_route' => 'api_dicom_mpps', '_controller' => 'App\\Controller\\DicomMppsController::update'], null, ['POST' => 0], null, false, false, null]],
        '/dicom/reconciliation' => [[['_route' => 'app_dicom_reconciliation', '_controller' => 'App\\Controller\\DicomReconciliationController::index'], null, null, null, false, false, null]],
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
                .'|/dicom/reconcilier/([^/]++)(*:34)'
                .'|/api/(?'
                    .'|examens/([^/]++)(?'
                        .'|(*:68)'
                        .'|/(?'
                            .'|dicom\\-source(*:92)'
                            .'|statut(*:105)'
                            .'|worklist(*:121)'
                        .')'
                        .'|(*:130)'
                    .')'
                    .'|patients/([^/]++)(?'
                        .'|(*:159)'
                        .'|/archiver(*:176)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        34 => [[['_route' => 'app_dicom_do_reconcile', '_controller' => 'App\\Controller\\DicomReconciliationController::reconcilier'], ['id'], ['POST' => 0], null, false, true, null]],
        68 => [
            [['_route' => 'examen_show', '_controller' => 'App\\Controller\\ExamenController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'examen_update', '_controller' => 'App\\Controller\\ExamenController::update'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        92 => [[['_route' => 'examen_dicom_source', '_controller' => 'App\\Controller\\ExamenController::dicomSource'], ['id'], ['GET' => 0], null, false, false, null]],
        105 => [[['_route' => 'examen_update_statut', '_controller' => 'App\\Controller\\ExamenController::updateStatut'], ['id'], ['PATCH' => 0], null, false, false, null]],
        121 => [[['_route' => 'examen_worklist', '_controller' => 'App\\Controller\\ExamenController::genererWorklist'], ['id'], ['POST' => 0], null, false, false, null]],
        130 => [[['_route' => 'examen_delete', '_controller' => 'App\\Controller\\ExamenController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        159 => [
            [['_route' => 'patient_show', '_controller' => 'App\\Controller\\PatientController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'patient_update', '_controller' => 'App\\Controller\\PatientController::update'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        176 => [
            [['_route' => 'patient_archive', '_controller' => 'App\\Controller\\PatientController::archiver'], ['id'], ['PATCH' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
