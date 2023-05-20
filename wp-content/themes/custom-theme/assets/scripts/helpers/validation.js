/**
 * Name verify
 */
function nameValidation( name = '' ) {
    if ( !name ) {
        return this.msg = "Bạn chưa nhập họ tên.";
    }

    if ( name.length > 200 ) {
        return this.msg = "Trường thông tin không được quá 200 ký tự.";
    }

    return true;
}

/**
 * Email verify 
 */
function emailValidation( email = '' ) {
    var regexEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var testEmail = regexEmail.test(email);

    if ( !email ) {
        return this.msg = "Bạn chưa nhập email.";
    }

    if ( email.length > 200 ) {
        return this.msg = "Trường thông tin không được quá 200 ký tự.";
    }

    if ( !testEmail ) {
        return this.msg = "Email không hợp lệ";
    }

    return testEmail;
}

/**
 * Phone verify
 */
function phoneValidation( phone = '' ) {
    var phoneTest = !isNaN(phone) && phone.length <= 11 && phone.length > 9;

    if ( !phone ) {
        return this.msg = "Bạn chưa nhập số điện thoại.";
    }

    if (!phoneTest) {
        return this.msg = "Số điện thoại không hợp lệ";
    }

    return phoneTest;
}