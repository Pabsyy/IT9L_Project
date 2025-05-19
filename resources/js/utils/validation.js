export const rules = {
    required: (value) => !!value || 'This field is required',
    email: (value) => {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return !value || pattern.test(value) || 'Invalid email address';
    },
    minLength: (min) => (value) => {
        return !value || value.length >= min || `Minimum length is ${min} characters`;
    },
    maxLength: (max) => (value) => {
        return !value || value.length <= max || `Maximum length is ${max} characters`;
    },
    numeric: (value) => {
        return !value || !isNaN(value) || 'Must be a number';
    },
    phone: (value) => {
        const pattern = /^\+?[\d\s-]{10,}$/;
        return !value || pattern.test(value) || 'Invalid phone number';
    },
    password: (value) => {
        const hasUpperCase = /[A-Z]/.test(value);
        const hasLowerCase = /[a-z]/.test(value);
        const hasNumbers = /\d/.test(value);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(value);
        
        if (!value) return true;
        if (value.length < 8) return 'Password must be at least 8 characters';
        if (!hasUpperCase) return 'Password must contain an uppercase letter';
        if (!hasLowerCase) return 'Password must contain a lowercase letter';
        if (!hasNumbers) return 'Password must contain a number';
        if (!hasSpecialChar) return 'Password must contain a special character';
        
        return true;
    },
    confirmPassword: (password) => (value) => {
        return value === password || 'Passwords do not match';
    }
};

export const validateForm = (formData, validationRules) => {
    const errors = {};
    let isValid = true;

    Object.keys(validationRules).forEach(field => {
        const value = formData[field];
        const fieldRules = validationRules[field];

        if (Array.isArray(fieldRules)) {
            for (const rule of fieldRules) {
                const result = rule(value);
                if (result !== true) {
                    errors[field] = result;
                    isValid = false;
                    break;
                }
            }
        } else {
            const result = fieldRules(value);
            if (result !== true) {
                errors[field] = result;
                isValid = false;
            }
        }
    });

    return { isValid, errors };
}; 