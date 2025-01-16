/******/ (() => { // webpackBootstrap
    /******/ 	// runtime can't be in strict mode because a global variable is assign and maybe created.
    /******/ 	var __webpack_modules__ = ({});
    /************************************************************************/
    /******/ 	// The module cache
    /******/ 	var __webpack_module_cache__ = {};
    /******/
    /******/ 	// The require function
    /******/ 	function __webpack_require__(moduleId) {
        /******/ 		// Check if module is in cache
        /******/ 		var cachedModule = __webpack_module_cache__[moduleId];
        /******/ 		if (cachedModule !== undefined) {
            /******/ 			return cachedModule.exports;
            /******/ 		}
        /******/ 		// Create a new module (and put it into the cache)
        /******/ 		var module = __webpack_module_cache__[moduleId] = {
            /******/ 			// no module.id needed
            /******/ 			// no module.loaded needed
            /******/ 			exports: {}
            /******/ 		};
        /******/
        /******/ 		// Execute the module function
        /******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ 		// Return the exports of the module
        /******/ 		return module.exports;
        /******/ 	}
    /******/
    /************************************************************************/
    var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
    (() => {
        "use strict";
// EXTERNAL MODULE: external "flarum.core.compat['forum/app']"
        const app = flarum.core.compat['forum/app'];
// EXTERNAL MODULE: external "flarum.core.compat['common/extend']"
        const extend = flarum.core.compat['common/extend'];
// EXTERNAL MODULE: external "flarum.core.compat['forum/utils/DiscussionControls']"
        const DiscussionControls = flarum.core.compat['forum/utils/DiscussionControls'];

        app.initializers.add('ayecode/flarum-restrictions', () => {
            extend(DiscussionControls.prototype, 'replyAction', function(items, discussion) {
                if (!discussion.canReply()) {
                    const message = m('div.Alert.Alert--warning', [
                        m('p', 'You must have a valid license to reply to this discussion.'),
                        m('p', [
                            m('a.Button.Button--primary', {
                                href: 'https://wpgeodirectory.com/downloads/location-manager/',
                                target: '_blank'
                            }, 'Purchase License')
                        ])
                    ]);

                    items.add('restrictionMessage', message, -10);
                }
            });
        });

    })();
    /******/ })();