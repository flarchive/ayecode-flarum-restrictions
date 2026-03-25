const app = flarum.core.compat['forum/app'];
const { extend } = flarum.core.compat['common/extend'];
const DiscussionPage = flarum.core.compat['components/DiscussionPage'];
const Page = flarum.core.compat['components/Page'];
const IndexPage = flarum.core.compat['components/IndexPage'];
const SessionDropdown = flarum.core.compat['components/SessionDropdown'];


app.initializers.add('ayecode/flarum-restrictions', function () {
    // Define array of allowed forums
    const allowedForums = []; // removed access to all forums ['general', 'geodirectory-core']; // Forums that are allowed to be seen without a license

    let currentAlert = null;
    let lastCheckedTag = null;

    // Clear alert when leaving any page
    extend(Page.prototype, 'oninit', function() {
        if (currentAlert) {
            app.alerts.dismiss(currentAlert);
            currentAlert = null;
        }
        lastCheckedTag = null;
    });

    extend(DiscussionPage.prototype, 'onupdate', function () {
        const discussionId = this.discussion ? this.discussion.id() : null;

        // Only check if we have a discussion and haven't shown alert yet
        if (discussionId && !currentAlert && !this.discussion.canReply()) {
            // Check if discussion has tags and none of them are in allowedForums
            const tags = this.discussion.tags();
            const hasNonAllowedTag = tags && !tags.some(tag => allowedForums.includes(tag.slug()));

            if (hasNonAllowedTag) {
                currentAlert = app.alerts.show(
                    { type: 'error' },
                    'You must have a valid license to reply to this discussion.'
                );
            }
        }
    });

    extend(IndexPage.prototype, 'onupdate', function () {
        const currentTag = this.currentTag && typeof this.currentTag === 'function' ? this.currentTag() : null;

        // Only check if tag has changed and we haven't shown alert yet
        if (currentTag && currentTag !== lastCheckedTag) {
            lastCheckedTag = currentTag;

            if (!allowedForums.includes(currentTag.slug()) && !currentTag.canStartDiscussion()) {
                currentAlert = app.alerts.show(
                    { type: 'error' },
                    'You must have a valid license to start a discussion in this section.'
                );
            }
        }
    });

    // Disable "Start Discussion" button on homepage if user can't post to any forum
    extend(IndexPage.prototype, 'actionItems', function (items) {
        const currentTag = this.currentTag && typeof this.currentTag === 'function' ? this.currentTag() : null;

        // If we're on the homepage (no specific tag selected)
        if (!currentTag) {
            // Check if user can start discussion in any available tag
            const tags = app.store.all('tags');
            const canPostToAnyForum = tags.some(tag => tag.canStartDiscussion());

            // If user can't post to any forum, disable the start discussion button
            if (!canPostToAnyForum && items.has('startDiscussion')) {
                items.remove('startDiscussion');
            }
        }
    });


    // Modify logout with AJAX
    extend(SessionDropdown.prototype, 'items', function(items) {
        const logOutItem = items._items.logOut;
        if (logOutItem && logOutItem.content) {
            logOutItem.content = m('button.Button.hasIcon', {
                onclick: function(e) {
                    e.preventDefault();

                    // Create form data
                    const formData = new FormData();
                    formData.append('nopriv_nonce', '103d78334b6e9b8a4089ebce577411ed');

                    // Make AJAX call
                    fetch('https://wpgeodirectory.com/support/wp-admin/admin-ajax.php?action=ayetheme_logout', {
                        method: 'POST',
                        credentials: 'include',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                app.alerts.show(
                                    { type: 'error' },
                                    'Logout failed. Please try again.'
                                );
                            }
                        })
                        .catch(error => {
                            app.alerts.show(
                                { type: 'error' },
                                'Logout failed. Please try again.'
                            );
                        });
                }
            }, [
                m('i.icon.fas.fa-sign-out-alt.Button-icon', {'aria-hidden': 'true'}),
                m('span.Button-label', 'Log Out')
            ]);
        }
    });
});