import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import DiscussionControls from 'flarum/forum/utils/DiscussionControls';
import Button from 'flarum/common/components/Button';

app.initializers.add('ayecode/flarum-restrictions', () => {
    extend(DiscussionControls, 'replyAction', function(items, discussion) {
        if (!discussion.canReply()) {
            // Replace the disabled reply button with our custom message
            const alertMessage = m('.Alert.Alert--warning', [
                m('p', "You must have a valid license to reply to this discussion."),
                m('p', [
                    m('a.Button.Button--primary', {
                        href: 'https://wpgeodirectory.com/downloads/location-manager/'
                    }, 'Purchase License')
                ])
            ]);

            // Add our message after the reply button
            items.after('reply', alertMessage);
        }
    });
});