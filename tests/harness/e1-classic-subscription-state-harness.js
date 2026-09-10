'use strict';

const fs = require('fs');
const path = require('path');
const vm = require('vm');

const ROOT = path.resolve(__dirname, '..', '..');
const SOURCE = path.join(ROOT, 'assets', 'js', 'subscription-checkout.js');
const source = fs.readFileSync(SOURCE, 'utf8');

let pass = 0;
let fail = 0;

function record(condition, description) {
    if (condition) {
        pass++;
        console.log('PASS: ' + description);
    } else {
        fail++;
        console.log('FAIL: ' + description);
    }
}

function createScene(loggedIn) {
    const state = {
        plan: 'monthly',
        interval: '',
        options: [],
        intervalVisible: true,
        handlers: {},
    };

    function createOption() {
        return {
            value: '',
            label: '',
            val(value) {
                this.value = String(value);
                return this;
            },
            text(value) {
                this.label = String(value);
                return this;
            },
        };
    }

    function planCollection() {
        return {
            length: 1,
            val(value) {
                if (arguments.length > 0) {
                    state.plan = String(value);
                    return this;
                }
                return state.plan;
            },
        };
    }

    function intervalCollection() {
        return {
            length: 1,
            val(value) {
                if (arguments.length > 0) {
                    state.interval = String(value);
                    return this;
                }
                return state.interval;
            },
            empty() {
                state.options = [];
                state.interval = '';
                return this;
            },
            append(option) {
                state.options.push({ value: option.value, label: option.label });
                if (state.options.length === 1) {
                    state.interval = option.value;
                }
                return this;
            },
            closest() {
                return {
                    show() {
                        state.intervalVisible = true;
                    },
                    hide() {
                        state.intervalVisible = false;
                    },
                };
            },
        };
    }

    function bodyCollection() {
        return {
            on(eventName, selectorOrHandler, maybeHandler) {
                const handler = typeof selectorOrHandler === 'function'
                    ? selectorOrHandler
                    : maybeHandler;
                state.handlers[eventName] = handler;
                return this;
            },
        };
    }

    const sandbox = {
        console,
        document: { body: {} },
        wcUser: { isLoggedIn: !!loggedIn },
    };

    function jquery(value) {
        if (typeof value === 'function') {
            value(jquery);
            return undefined;
        }
        if (value === sandbox.document.body) {
            return bodyCollection();
        }
        if (value === 'select[name="upay_subscription_plan"]') {
            return planCollection();
        }
        if (value === '#upay_subscription_interval') {
            return intervalCollection();
        }
        if (value === '<option></option>') {
            return createOption();
        }
        throw new Error('Unexpected jQuery selector in harness: ' + String(value));
    }

    jquery.each = function each(object, callback) {
        Object.keys(object).forEach(function eachKey(key) {
            callback(key, object[key]);
        });
    };

    sandbox.jQuery = jquery;
    vm.runInNewContext(source, sandbox, { filename: SOURCE });

    return {
        state,
        trigger(eventName) {
            const handler = state.handlers[eventName];
            if (typeof handler !== 'function') {
                throw new Error('No handler registered for ' + eventName);
            }
            handler();
        },
    };
}

console.log('Running e1-classic-subscription-state-harness.js');

{
    const scene = createScene(true);
    scene.state.interval = '2';
    scene.trigger('updated_checkout');
    record(
        scene.state.interval === '2',
        'valid selected monthly interval survives updated_checkout'
    );
}

{
    const scene = createScene(true);
    scene.state.interval = '9';
    scene.trigger('updated_checkout');
    record(
        scene.state.interval === '',
        'invalid interval is reset after updated_checkout'
    );
}

{
    const scene = createScene(true);
    scene.state.plan = 'one_time';
    scene.state.interval = '2';
    scene.trigger('updated_checkout');
    record(
        scene.state.interval === '0' && scene.state.intervalVisible === false,
        'one-time checkout forces interval 0 and hides interval row'
    );
}

{
    const scene = createScene(false);
    record(
        scene.state.plan === 'one_time'
            && scene.state.interval === '0'
            && scene.state.intervalVisible === false,
        'logged-out subscription selection is coerced to one-time'
    );
}

{
    const scene = createScene(true);
    scene.state.plan = 'weekly';
    scene.state.interval = '3';
    scene.trigger('updated_checkout');
    record(
        scene.state.interval === '3',
        'valid selected weekly interval survives updated_checkout'
    );
}

{
    const scene = createScene(true);
    scene.state.interval = '2';
    scene.state.plan = 'weekly';
    scene.trigger('change');
    record(
        scene.state.interval === '',
        'deliberate plan change resets an inherited interval even when numeric value remains valid'
    );
}

console.log('Classic subscription state results: ' + pass + ' passed, ' + fail + ' failed.');
if (fail > 0) {
    process.exitCode = 1;
}
