/********************
 * Class Observable 
 ********************/


module.exports = Observable;

function Observable() {};

Observable.prototype.on = function(event, callback) {
    this.listeners = this.listeners || {};

    if (event in this.listeners) {
        this.listeners[event].push(callback);
    } else {
        this.listeners[event] = [callback];
    }
    
    return this;
}

Observable.prototype.one = function(event, callback) {
    this.oneTimeListenersIndexes = this.oneTimeListenersIndexes || {};
    
    if (!this.oneTimeListenersIndexes[event]) this.oneTimeListenersIndexes[event] = [];
    
    this.on(event, callback);
    
    this.oneTimeListenersIndexes[event].push(this.listeners[event].length - 1);
    
    return this;
}

Observable.prototype.off = function(event) {
    if (this.listeners) {
        if (event) {
            delete this.listeners[event];
        } else {
            this.listeners = null;
        }
    }
    
    return this;
}

Observable.prototype.trigger = function(event) {
    if (this.listeners && event in this.listeners) {
        this.invoke.call(this, event, Array.prototype.slice.call(arguments, 1));
    }

    if (this.listeners && '*' in this.listeners) {
        this.invoke.call(this, '*', arguments);
    }
    
    return this;
}

Observable.prototype.invoke = function(event, params) {
    var eventListeners = this.listeners[event], survivalEventListeners = [];
    
    for (var i = 0, len = eventListeners.length; i < len; i++) {
        var listener = eventListeners[i];
        
        listener.apply(this, params);
        
        if (!this.oneTimeListenersIndexes || !this.oneTimeListenersIndexes[event] || !this.oneTimeListenersIndexes[event].includes(i)) {
            survivalEventListeners.push(listener);
        }
    }
    
    this.oneTimeListenersIndexes && delete this.oneTimeListenersIndexes[event];
    
    survivalEventListeners.length ? this.listeners[event] = survivalEventListeners : delete this.listeners[event];
}
