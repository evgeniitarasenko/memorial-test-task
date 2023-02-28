<script setup>
import {watch, reactive, ref} from 'vue'
import useModal from './../services/useModal'
import userPermissionsConstants from "../constants/userPermissions";

let {visible, show, hide} = useModal()

// Roles
let roles = reactive({})

watch(visible, async (newVisible) => {
    if (newVisible) {
        axios.get('/api/user-roles').then((response) => {
            roles.value = response.data;
        })
    }
})

// List
let inputtedEmail = ref(null)
let errors = reactive({})

let recipients = reactive([]);
let message = ref(null);

const isValidated = (email) => {
    cleanErrors();

    // Check unique
    let index = recipients.findIndex((recipient) => recipient.email === email)
    if (index !== -1) {
        errors.inputted_email = 'Email already exists'

        return false;
    }

    // Check valid email
    // TODO: move to validation service
    let validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    if (!email.match(validRegex)) {
        errors.inputted_email = 'Email is not valid'

        return false;
    }

    return true;
}

const add = () => {
    if (!inputtedEmail.value || !isValidated(inputtedEmail.value)) {
        return;
    }

    recipients.push({
        'email': inputtedEmail.value,
        'name': 'Lorem',
        'role': userPermissionsConstants.DEFAULT_USER_ROLE,
    })
}

const remove = (email) => {
    let index = recipients.findIndex((recipient) => recipient.email === email)

    if (index !== -1) {
        recipients.splice(index, 1)
    }
}

// Sending
const send = () => {
    if (!recipients.length) {
        return
    }

    cleanErrors();

    axios.post('/api/invite', {
        recipients,
        message: message.value,
    }).then((response) => {
        hide()

        let countRecipients = response.data.data.length;
        // TODO: another modal, toastr or sth
        console.log(`${countRecipients} invitations will be sent`)
    }).catch((error) => {
        Object.entries(error.response.data.errors).forEach(([field, error]) => {
            errors[field] = error.join(' ')
        });
    })
}

const cleanErrors = () => {
    for (let key in errors) {
        delete errors[key];
    }
}

</script>

<template>
    <div>
        <button @click="show">Show</button>

        <Teleport to="#modals">
            <div class="modal-box" v-if="visible">
                <div class="row pb-4">
                    <div class="col-md-11">Invite others</div>
                    <div class="col-md-1">
                        <div class="close" @click="hide"></div>
                    </div>
                </div>

                <div class="row add-form mb-4">
                    <div class="col-md-10">
                        <input placeholder="Enter people E-mails" v-model="inputtedEmail"/>
                        <div>
                            {{ errors.inputted_email }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div @click="add" class="add-button">Add</div>
                    </div>
                </div>

                <div
                    class="row pb-4"
                    v-for="(recipient, index) in recipients"
                    :key="recipient.email"
                >
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-7">
                                <div><small>{{ recipient.name }}</small></div>
                                <div v-if="errors[`recipients.${index}.email`]">{{ errors[`recipients.${index}.email`] }}</div>

                                <div><strong>{{ recipient.email }}</strong></div>
                                <div v-if="errors[`recipients.${index}.name`]">{{ errors[`recipients.${index}.name`] }}</div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-control">
                                    <select class="form-select" v-model="recipient.role">
                                        <option
                                            v-for="(label, key) in roles.value"
                                            :key="key"
                                            :value="key"
                                        >
                                            {{ label }}
                                        </option>
                                    </select>
                                </div>

                                <div v-if="errors[`recipients.${index}.role`]">{{ errors[`recipients.${index}.role`] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 remove-box">
                        <div class="remove-button" @click="remove(recipient.email)"></div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <input placeholder="Personal message (optional)" v-model="message"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end align-items-center">
                        <small v-if="recipients.length">{{ recipients.length }} recipients</small>
                        <div class="send-button ml-2" @click="send">Send</div>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>
