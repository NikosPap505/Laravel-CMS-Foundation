<?php

return [
    /*
     * Editor to use when clicking links to files.
     */
    'editor' => env('IGNITION_EDITOR', 'vscode'),

    /*
     * Show all frames, including vendor frames.
     */
    'hide_vendor_frames' => false,

    /*
     * The themes that should be available in the Vue theme selector.
     */
    'themes' => [
        'light',
        'dark',
    ],

    /*
     * The Ignition remote path prefix you wish to remove from stack traces.
     */
    'remote_path_prefix' => base_path(),

    /*
     * If Ignition should display stack trace frames collapsed by default.
     */
    'collapse_frames_by_default' => false,

    /*
     * Enable/disable share button in Ignition error page
     */
    'enable_share_button' => env('IGNITION_ENABLE_SHARE_BUTTON', true),
];