<?php

namespace BeyondCode\SlackNotificationChannel\Messages;

class SlackAttachmentDividerBlock
{
    /**
     * Get the array representation of the attachment block.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => 'divider',
        ];
    }
}