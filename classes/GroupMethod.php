<?php
/**
 * Created by PhpStorm.
 * User: Specialist001
 * Date: 21/10/21
 * Time: 12:10
 */

namespace Specialist\Feedback\Classes;


use Backend\Widgets\Form;
use Specialist\Feedback\Controllers\Channels;
use Specialist\Feedback\Models\Channel;
use Illuminate\Support\Facades\Mail;

class GroupMethod implements Method
{

    public function boot()
    {
        Channels::extendFormFields(function (Form $form, $model) {
            $form->addFields([
                    'method_data[channels]' => [
                        'label' => "specialist.feedback::lang.channel.many",
                        'commentAbove' => "specialist.feedback::lang.method.group.channels_comment",
                        'type' => 'checkboxlist',
                        'options' => Channel::all()->lists('name', 'id'),
                        'required' => true,
                        'trigger' => [
                            'action' => "show",
                            'field' => "method",
                            'condition' => "value[group]"
                        ]
                    ]
                ]
            );
        });
    }

    public function send($methodData, $data)
    {
        foreach ($methodData['channels'] as $channelId) {
            /** @var Channel $channel */
            $channel = Channel::find($channelId);
            if ($channel) {
                $channel->prevent_save_database = true;
                $channel->send($data);

                $channel->prevent_save_database = $channel->getOriginal('prevent_save_database');
            }
            else {
                \Log::warning('FeedbackPlugin: One of your group channels is trying to send a message to an unknown channel.');
            }
        }
    }

}
