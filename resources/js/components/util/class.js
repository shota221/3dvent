var ClassUtils = {};

module.exports = ClassUtils;

/**
 * Class Extend
 * 
 * ex)
 * ChildClass = Utils.Extend(SuperClass, function ChildClass() {
 *          // Chlid constructor
 *      }), {
 *          // Child methods
 *      });
 */
ClassUtils.Extend = function(SuperClass, ChildClass, ChildClassMethod) {
    var SuperClass = typeof SuperClass === "string" ? ClassUtils.classes[SuperClass] : (SuperClass || Object);
    
    ChildClass.prototype.__extends__ = (ChildClass.prototype.__extends__ || [])
        .concat([SuperClass.prototype.constructor.name])
        .concat(SuperClass.prototype.__extends__ || []);
    
    ChildClass.prototype = $.extend({}, SuperClass.prototype, ChildClass.prototype, {
        constructor   : ChildClass.prototype.constructor,
        __super__     : SuperClass.prototype,
    });
    
    if (ChildClassMethod && !$.isEmptyObject(ChildClassMethod)) {
        // forはプロトタイプチェーンをたどる、hasOwnPropertyはたどらない
        for (var key in ChildClassMethod || {}) if (ChildClassMethod.hasOwnProperty(key)) {
            ChildClass.prototype[key] = ChildClassMethod[key];
        }
    }
    
    return ChildClass;
}

/**
 * Class 定義
 * 
 * 継承のないCLass
 */
ClassUtils.Class = function(Class, ClassMethod) {
    return ClassUtils.Extend(null, Class, ClassMethod);
}

/**
 * Classが対象インスタンスかどうか調べる
 */
ClassUtils.instanceOf = function(Obj, className) {
    return (className === Obj.constructor.name) || (Obj.__extends__ && (0 <= Obj.__extends__.indexOf(className)));
}