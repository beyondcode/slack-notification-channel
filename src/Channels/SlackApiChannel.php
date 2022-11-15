<?php

namespace BeyondCode\SlackNotificationChannel\Channels;

use BeyondCode\SlackNotificationChannel\Messages\SlackAttachmentBlock;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\Notification;
use BeyondCode\SlackNotificationChannel\Messages\SlackMessage;
use BeyondCode\SlackNotificationChannel\Messages\SlackAttachment;
use BeyondCode\SlackNotificationChannel\Messages\SlackAttachmentField;

class SlackApiChannel
{
    const API_ENDPOINT = 'https://slack.com/api/chat.postMessage';

    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /** @var string */
    protected $token;

    /** @var string */
    protected $channel;

    /** @var string */
    protected static $channelDriverName = 'slack';

    /**
     * Create a new Slack channel instance.
     *
     * @param  \GuzzleHttp\Client  $http
     * @return void
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;

        $this->token = null;
    }

    public static function channelDriverName(string $channelDriverName)
    {
        self::$channelDriverName = $channelDriverName;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $config = $notifiable->routeNotificationFor(self::$channelDriverName, $notification)) {
            return;
        }

        $this->token = $config['token'];
        $this->channel = $config['channel'] ?? null;


        $methodName = 'to' . ucfirst(self::$channelDriverName);

        $response = $this->http->post(
            self::API_ENDPOINT,
            $this->buildJsonPayload(

                $notification->$methodName($notifiable)
            )
        );

        if(method_exists($notification, 'response')){
            return $notification->response($response);
        }

        return $response;
    }

    /**
     * Build up a JSON payload for the Slack webhook.
     *
     * @param  \Illuminate\Notifications\Messages\SlackMessage  $message
     * @return array
     */
    protected function buildJsonPayload(SlackMessage $message)
    {
        $optionalFields = array_filter([
            'channel' => data_get($message, 'channel', $this->channel),
            'icon_emoji' => data_get($message, 'icon'),
            'icon_url' => data_get($message, 'image'),
            'link_names' => data_get($message, 'linkNames'),
            'unfurl_links' => data_get($message, 'unfurlLinks'),
            'unfurl_media' => data_get($message, 'unfurlMedia'),
            'username' => data_get($message, 'username'),
            'thread_ts' => data_get($message, 'threadTimestamp'),
            'reply_broadcast' => data_get($message, 'threadBroadcast')
        ]);

        $payload = [
            'json' => array_merge([
                'text' => $message->content,
                'attachments' => $this->attachments($message),
            ], $optionalFields),
        ];


        $payload['headers'] = [
            'Content-type' => 'application/json; charset=UTF-8',
            'Authorization' => 'Bearer '.$this->token,
        ];

        return array_merge($payload, $message->http);
    }

    /**
     * Format the message's attachments.
     *
     * @param  \Illuminate\Notifications\Messages\SlackMessage  $message
     * @return array
     */
    protected function attachments(SlackMessage $message)
    {
        return collect($message->attachments)->map(function ($attachment) use ($message) {
            return array_filter([
                'actions' => $attachment->actions,
                'author_icon' => $attachment->authorIcon,
                'author_link' => $attachment->authorLink,
                'author_name' => $attachment->authorName,
                'blocks' => $this->blocks($attachment),
                'color' => $attachment->color ?: $message->color(),
                'callback_id' => $attachment->callbackId,
                'fallback' => $attachment->fallback,
                'fields' => $this->fields($attachment),
                'footer' => $attachment->footer,
                'footer_icon' => $attachment->footerIcon,
                'image_url' => $attachment->imageUrl,
                'mrkdwn_in' => $attachment->markdown,
                'pretext' => $attachment->pretext,
                'text' => $attachment->content,
                'thumb_url' => $attachment->thumbUrl,
                'title' => $attachment->title,
                'title_link' => $attachment->url,
                'ts' => $attachment->timestamp,
            ]);
        })->all();
    }

    /**
     * Format the attachment's fields.
     *
     * @param  \BeyondCode\SlackNotificationChannel\Messages\SlackAttachment $attachment
     * @return array
     */
    protected function fields(SlackAttachment $attachment)
    {
        return collect($attachment->fields)->map(function ($value, $key) {
            if ($value instanceof SlackAttachmentField) {
                return $value->toArray();
            }

            return ['title' => $key, 'value' => $value, 'short' => true];
        })->values()->all();
    }

    /**
     * Format the attachment's blocks.
     *
     * @param  \BeyondCode\SlackNotificationChannel\Messages\SlackAttachment $attachment
     * @return array
     */
    protected function blocks(SlackAttachment $attachment)
    {
        return collect($attachment->blocks)->map(function ($value) {
            return $value->toArray();
        })->values()->all();
    }
}
