; (function (factory) {
    module.exports = factory(
        jQuery,
        require('../components/util/class.js'),
        require('../components/form.class.js')
        );
}(function ($, ClassUtils, BaseFormClass) {
    'use strict'

    let FormOrgRegist;

    //BaseFormClassを継承したFormOrgRegistクラスを返す
    return FormOrgRegist = ClassUtils.Extend(BaseFormClass, function FormOrgRegist($elm) {//コンストラクタ定義
        //コールメソッドによりオブジェクトのコンストラクタを連鎖
        FormOrgRegist.prototype.__super__.constructor.call(this, $elm);
        //この親クラスのコンストラクタにより$formが$elmと紐づく

        this.$fieldOrganizationNameInput = this.$form.find('input[name="organization_name"]');

        this.$fieldRepresentativeNameInput = this.$form.find('input[name="representative_name"]');

        this.$fieldRepresentativeEmailInput = this.$form.find('input[name="representative_email"]');

        this.$fieldOrganizationCodeInput = this.$form.find('input[name="organization_code"]');
    });
}));