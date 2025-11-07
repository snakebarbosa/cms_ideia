<template>
    <div class="password-strength-validator">
        <!-- Current Password Field -->
        <div class="form-group">
            <div class="form-line" :class="{ 'focused': currentPasswordFocused }">
                <input 
                    type="password" 
                    class="form-control" 
                    name="current_password" 
                    v-model="currentPassword"
                    @focus="currentPasswordFocused = true"
                    @blur="currentPasswordFocused = false"
                    required
                >
                <label class="form-label">Password Atual *</label>
            </div>
        </div>

        <!-- New Password Field -->
        <div class="form-group">
            <div class="form-line" :class="{ 'focused': newPasswordFocused }">
                <input 
                    type="password" 
                    class="form-control" 
                    name="new_password" 
                    v-model="newPassword"
                    @input="checkStrength"
                    @focus="newPasswordFocused = true"
                    @blur="newPasswordFocused = false"
                    required
                >
                <label class="form-label">Nova Password *</label>
            </div>
        </div>

        <!-- Password Strength Indicator -->
        <div v-if="newPassword.length > 0" class="password-strength-bar">
            <div class="progress">
                <div 
                    class="progress-bar" 
                    :class="strengthClass" 
                    :style="{ width: strength + '%' }"
                    role="progressbar"
                >
                    <span>{{ strengthText }}</span>
                </div>
            </div>
        </div>

        <!-- Password Requirements -->
        <div v-if="newPassword.length > 0" class="alert alert-info password-requirements">
            <strong>Requisitos da Password:</strong>
            <ul class="requirements-list">
                <li :class="{ 'valid': requirements.length }">
                    <i class="material-icons">{{ requirements.length ? 'check_circle' : 'cancel' }}</i>
                    Mínimo de 8 caracteres
                </li>
                <li :class="{ 'valid': requirements.uppercase }">
                    <i class="material-icons">{{ requirements.uppercase ? 'check_circle' : 'cancel' }}</i>
                    Pelo menos 1 letra maiúscula
                </li>
                <li :class="{ 'valid': requirements.lowercase }">
                    <i class="material-icons">{{ requirements.lowercase ? 'check_circle' : 'cancel' }}</i>
                    Pelo menos 1 letra minúscula
                </li>
                <li :class="{ 'valid': requirements.number }">
                    <i class="material-icons">{{ requirements.number ? 'check_circle' : 'cancel' }}</i>
                    Pelo menos 1 número
                </li>
                <li :class="{ 'valid': requirements.special }">
                    <i class="material-icons">{{ requirements.special ? 'check_circle' : 'cancel' }}</i>
                    Pelo menos 1 símbolo especial (@$!%*?&amp;#)
                </li>
            </ul>
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group">
            <div class="form-line" :class="{ 'focused': confirmPasswordFocused }">
                <input 
                    type="password" 
                    class="form-control" 
                    name="new_password_confirmation" 
                    v-model="confirmPassword"
                    @input="checkMatch"
                    @focus="confirmPasswordFocused = true"
                    @blur="confirmPasswordFocused = false"
                    required
                >
                <label class="form-label">Confirmar Nova Password *</label>
            </div>
            <span v-if="confirmPassword.length > 0 && !passwordsMatch" class="text-danger">
                <i class="material-icons" style="font-size: 14px; vertical-align: middle;">warning</i>
                As passwords não coincidem
            </span>
            <span v-if="confirmPassword.length > 0 && passwordsMatch" class="text-success">
                <i class="material-icons" style="font-size: 14px; vertical-align: middle;">check_circle</i>
                As passwords coincidem
            </span>
        </div>

        <!-- Hidden input to control form submission -->
        <input type="hidden" name="form_valid" :value="isFormValid ? '1' : '0'">
    </div>
</template>

<script>
export default {
    name: 'PasswordStrength',
    data() {
        return {
            currentPassword: '',
            newPassword: '',
            confirmPassword: '',
            currentPasswordFocused: false,
            newPasswordFocused: false,
            confirmPasswordFocused: false,
            requirements: {
                length: false,
                uppercase: false,
                lowercase: false,
                number: false,
                special: false
            },
            strength: 0,
            strengthText: '',
            strengthClass: '',
            passwordsMatch: false
        }
    },
    computed: {
        isFormValid() {
            return this.currentPassword.length >= 6 &&
                   this.allRequirementsMet &&
                   this.passwordsMatch;
        },
        allRequirementsMet() {
            return this.requirements.length &&
                   this.requirements.uppercase &&
                   this.requirements.lowercase &&
                   this.requirements.number &&
                   this.requirements.special;
        }
    },
    methods: {
        checkStrength() {
            const password = this.newPassword;
            
            // Check individual requirements
            this.requirements.length = password.length >= 8;
            this.requirements.uppercase = /[A-Z]/.test(password);
            this.requirements.lowercase = /[a-z]/.test(password);
            this.requirements.number = /\d/.test(password);
            this.requirements.special = /[@$!%*?&#]/.test(password);
            
            // Calculate strength
            let metCount = 0;
            if (this.requirements.length) metCount++;
            if (this.requirements.uppercase) metCount++;
            if (this.requirements.lowercase) metCount++;
            if (this.requirements.number) metCount++;
            if (this.requirements.special) metCount++;
            
            this.strength = (metCount / 5) * 100;
            
            // Set strength text and class
            if (this.strength <= 20) {
                this.strengthText = 'Muito Fraca';
                this.strengthClass = 'bg-red';
            } else if (this.strength <= 40) {
                this.strengthText = 'Fraca';
                this.strengthClass = 'bg-orange';
            } else if (this.strength <= 60) {
                this.strengthText = 'Média';
                this.strengthClass = 'bg-cyan';
            } else if (this.strength <= 80) {
                this.strengthText = 'Boa';
                this.strengthClass = 'bg-light-green';
            } else {
                this.strengthText = 'Muito Boa';
                this.strengthClass = 'bg-green';
            }
            
            // Check if passwords match when new password changes
            this.checkMatch();
        },
        checkMatch() {
            this.passwordsMatch = this.confirmPassword.length > 0 && 
                                  this.newPassword === this.confirmPassword;
        }
    },
    watch: {
        isFormValid(newVal) {
            // Emit event to parent to enable/disable submit button
            this.$emit('validity-changed', newVal);
        }
    }
}
</script>

<style scoped>
.password-strength-validator {
    width: 100%;
}

.password-strength-bar {
    margin-bottom: 15px;
}

.password-strength-bar .progress {
    height: 25px;
    margin-bottom: 10px;
}

.password-strength-bar .progress-bar {
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
}

.password-requirements {
    background-color: #e3f2fd !important;
    border-left: 4px solid #2196F3;
    padding: 15px;
    margin-bottom: 20px;
}

.password-requirements strong {
    color: #1976D2;
    display: block;
    margin-bottom: 10px;
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirements-list li {
    padding: 5px 0;
    color: #666;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.requirements-list li i {
    font-size: 18px;
    margin-right: 8px;
}

.requirements-list li.valid {
    color: #4CAF50;
    font-weight: 500;
}

.requirements-list li.valid i {
    color: #4CAF50;
}

.requirements-list li:not(.valid) i {
    color: #F44336;
}

.text-danger {
    color: #F44336;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.text-success {
    color: #4CAF50;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.form-line.focused {
    border-bottom: 2px solid #2196F3;
}

.bg-red {
    background-color: #F44336 !important;
}

.bg-orange {
    background-color: #FF9800 !important;
}

.bg-cyan {
    background-color: #00BCD4 !important;
}

.bg-light-green {
    background-color: #8BC34A !important;
}

.bg-green {
    background-color: #4CAF50 !important;
}
</style>
