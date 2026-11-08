// Exercise the actual no-build script against the WordPress store contract.
const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
let thumbnail = 0;
let callback;
let locked = false;
let notice = false;
const editor = {
  getCurrentPostType: () => 'post',
  getEditedPostAttribute: () => thumbnail,
  lockPostSaving: (key) => { assert.equal(key, 'aisa-featured'); locked = true; },
  unlockPostSaving: () => { locked = false; }
};
const notices = { createWarningNotice: () => { notice = true; }, removeNotice: () => { notice = false; } };
vm.runInNewContext(fs.readFileSync(__dirname + '/../assets/js/editor-featured-required.js', 'utf8'), {
  wp: { domReady: (fn) => fn(), i18n: { __: (text) => text }, data: {
    select: () => editor, dispatch: (store) => store === 'core/editor' ? editor : notices,
    subscribe: (fn) => { callback = fn; }
  } }
});
assert.ok(locked && notice);
thumbnail = 12;
callback();
assert.ok(!locked && !notice);
thumbnail = 0;
callback();
assert.ok(locked && notice);
console.log('PASS editor: blocks without image, unlocks with image, blocks removal');
