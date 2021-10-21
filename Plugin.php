<?php namespace Specialist\Feedback;

use System\Classes\PluginBase;

/**
 * feedback Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'specialist.feedback::lang.plugin.name',
            'description' => 'specialist.feedback::lang.plugin.description',
            'author'      => 'Specialist',
            'icon'        => 'icon-comments-o'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register()
    {
        \Validator::extend("emails", function($attribute, $value, $parameters) {
            $rules = [
                'email' => 'required|email',
            ];

            if (!is_array($value)) {
                $emails = explode(',', $value);
            }
            else {
                $emails = [$value];
            }

            foreach ($emails as $email) {
                $data = [
                    'email' => trim($email)
                ];
                $validator = \Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }

            return true;
        });
    }

    public function registerComponents()
    {
        return [
            '\Specialist\Feedback\Components\Feedback' => 'feedback'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     */
    public function registerPermissions()
    {
        return [
            'specialist.feedback.manage' => ['label' => 'specialist.feedback::lang.permissions.feedback.manage', 'tab' => 'cms::lang.permissions.name'],
            'specialist.feedback.settings.channel' => ['label' => 'specialist.feedback::lang.permissions.settings.channel', 'tab' => 'system::lang.permissions.name']
        ];
    }

    /**
     * Registers any mail templates implemented by this plugin.
     * The templates must be returned in the following format:
     * ['acme.blog::mail.welcome' => 'This is a description of the welcome template'],
     * ['acme.blog::mail.forgot_password' => 'This is a description of the forgot password template'],
     */
    public function registerMailTemplates()
    {
        return [
            'specialist.feedback::base-email' => \Lang::get('specialist.feedback::lang.mail_template.description')
        ];
    }

    public function registerNavigation()
    {
        return [
            'feedback' => [
                'label'       => 'specialist.feedback::lang.plugin.name',
                'url'         => \Backend::url('specialist/feedback/feedbacks'),
                'icon'        => 'icon-comments-o',
                'permissions' => ['specialist.feedback.manage'],

                'sideMenu' => [
                    'feedbacks' => [
                        'label'       => 'specialist.feedback::lang.navigation.menu.side.feedbacks',
                        'icon'        => 'icon-inbox',
                        'url'         => \Backend::url('specialist/feedback/feedbacks'),
                        'permissions' => ['specialist.feedback.manage'],
                    ],
                    'archived' => [
                        'label'       => 'specialist.feedback::lang.navigation.menu.side.archived',
                        'icon'        => 'icon-archive',
                        'url'         => \Backend::url('specialist/feedback/feedbacks/archived'),
                        'permissions' => ['specialist.feedback.manage']
                    ],
                ]

            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'channels' => [
                'label' => 'specialist.feedback::lang.channel.many',
                'description' => 'specialist.feedback::lang.navigation.menu.settings.channels.description',
                'category' => 'specialist.feedback::lang.plugin.name',
                'icon' => 'icon-arrows',
                'url' => \Backend::url('specialist/feedback/channels'),
                'order' => 500,
                'keywords' => 'feedback channel',
                'permissions' => ['specialist.feedback.settings.channel']
            ]
        ];
    }


}
