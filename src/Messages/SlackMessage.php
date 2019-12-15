<?php

namespace BeyondCode\SlackNotificationChannel\Messages;

use Closure;

class SlackMessage
{

    /**
     * The "level" of the notification (info, success, warning, error).
     *
     * @var string
     */
    public $level = 'info';

    /**
     * Whether to post the message as the authed user, instead of as a bot.
     *
     * @var bool
     */
    public $asUser;

    /**
     * The username to send the message from.
     *
     * @var string|null
     */
    public $username;

    /**
     * The user emoji icon for the message.
     *
     * @var string|null
     */
    public $icon;

    /**
     * The user image icon for the message.
     *
     * @var string|null
     */
    public $image;

    /**
     * The channel to send the message on.
     *
     * @var string|null
     */
    public $channel;

    /**
     * The text content of the message.
     *
     * @var string
     */
    public $content;

    /**
     * Indicates if channel names and usernames should be linked.
     *
     * @var bool
     */
    public $linkNames = 0;

    /**
     * Indicates if you want a preview of links inlined in the message.
     *
     * @var bool
     */
    public $unfurlLinks;

    /**
     * Indicates if you want a preview of links to media inlined in the message.
     *
     * @var bool
     */
    public $unfurlMedia;

    /**
     * The message's attachments.
     *
     * @var array
     */
    public $attachments = [];

    /**
     * Additional request options for the Guzzle HTTP client.
     *
     * @var array
     */
    public $http = [];

    /**
     * The attachment's threadTimestamp.
     *
     * @var string
     */
    public $threadTimestamp;

    /**
     * Broadcasts the thread reply
     *
     * @var bool
     */
    public $threadBroadcast = false;

    /**
     * Indicate that the notification gives information about an operation.
     *
     * @return $this
     */
    public function info()
    {
        $this->level = 'info';

        return $this;
    }

    /**
     * Indicate that the notification gives information about a successful operation.
     *
     * @return $this
     */
    public function success()
    {
        $this->level = 'success';

        return $this;
    }

    /**
     * Indicate that the notification gives information about a warning.
     *
     * @return $this
     */
    public function warning()
    {
        $this->level = 'warning';

        return $this;
    }

    /**
     * Indicate that the notification gives information about an error.
     *
     * @return $this
     */
    public function error()
    {
        $this->level = 'error';

        return $this;
    }

    /**
     * Set a custom username and optional emoji icon for the Slack message.
     *
     * @param  string  $username
     * @param  string|null  $icon
     * @return $this
     */
    public function from($username, $icon = null)
    {
        $this->username = $username;

        if (! is_null($icon)) {
            $this->icon = $icon;
        }

        return $this;
    }

    /**
     * Whether to post the message as the authed user, instead of as a bot.
     *
     * @param  bool   $asUser
     * @return $this
     */
    public function asUser($asUser)
    {
        $this->asUser = $asUser;

        return $this;
    }

    /**
     * Set a custom image icon the message should use.
     *
     * @param  string  $image
     * @return $this
     */
    public function image($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set the Slack channel the message should be sent to.
     *
     * @param  string  $channel
     * @return $this
     */
    public function to($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the content of the Slack message.
     *
     * @param  string  $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Define an attachment for the message.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function attachment(Closure $callback)
    {
        $this->attachments[] = $attachment = new SlackAttachment;

        $callback($attachment);

        return $this;
    }

    /**
     * Get the color for the message.
     *
     * @return string|null
     */
    public function color()
    {
        switch ($this->level) {
            case 'success':
                return 'good';
            case 'error':
                return 'danger';
            case 'warning':
                return 'warning';
        }
    }

    /**
     * Find and link channel names and usernames.
     *
     * @return $this
     */
    public function linkNames()
    {
        $this->linkNames = 1;

        return $this;
    }

    /**
     * Unfurl links to rich display.
     *
     * @param  string  $unfurl
     * @return $this
     */
    public function unfurlLinks($unfurl)
    {
        $this->unfurlLinks = $unfurl;

        return $this;
    }

    /**
     * Unfurl media to rich display.
     *
     * @param  string  $unfurl
     * @return $this
     */
    public function unfurlMedia($unfurl)
    {
        $this->unfurlMedia = $unfurl;

        return $this;
    }

    /**
     * Set additional request options for the Guzzle HTTP client.
     *
     * @param  array  $options
     * @return $this
     */
    public function http(array $options)
    {
        $this->http = $options;

        return $this;
    }

    /**
     * Set the threadTimestamp.
     *
     * @param  \DateTimeInterface|\DateInterval|int  $threadTimestamp
     * @return $this
     */
    public function threadTimestamp($threadTimestamp)
    {
        $this->threadTimestamp = $threadTimestamp;

        return $this;
    }

    /**
     * Set the thread to Broadcast
     *
     * @param bool $threadBroadcast
     * @return $this
     */
    public function threadBroadcast($threadBroadcast = true)
    {
        $this->threadBroadcast = $threadBroadcast;

        return $this;
    }
}
