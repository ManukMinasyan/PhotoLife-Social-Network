<template>
    <app-layout>
        <div class="container">
            <div class="columns">
                <div class="column">
                    <section class="box p-0 mt-30">
                        <b-tabs :animated="false">
                            <b-tab-item label="Edit profile">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content">
                                            <div class="field is-horizontal mb-20 profile-photo">
                                                <div class="field-label is-normal">
                                                    <label class="label">
                                                        <figure class="mr-0">
                                                            <p class="image is-48x48">
                                                                <img class="avatar is-rounded"
                                                                     :src="previewAvatar ? previewAvatar : AUTH_USER.avatar"
                                                                     id="target">
                                                            </p>
                                                        </figure>
                                                    </label>
                                                </div>
                                                <div class="field-body">
                                                    <div class="field">
                                                        <div class="control is-expanded">
                                                            <span class="is-size-5">
                                                                {{ AUTH_USER.username }}
                                                            </span>
                                                            <div>
                                                                <b-field class="file">
                                                                    <b-upload v-model="user.avatar" accept="image/*">
                                                                        <a>Change Profile Photo</a>
                                                                    </b-upload>
                                                                </b-field>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <b-field label="Name"
                                                     :type="{'is-danger': hasError('name')}"
                                                     :message="getError('name')"
                                                     horizontal>
                                                <b-input v-model="user.name"></b-input>
                                            </b-field>
                                            <b-field label="Username"
                                                     :type="{'is-danger': hasError('username')}"
                                                     :message="getError('username')"
                                                     horizontal>
                                                <b-input v-model="user.username"></b-input>
                                            </b-field>
                                            <b-field label="Website"
                                                     :type="{'is-danger': hasError('website')}"
                                                     :message="getError('website')"
                                                     horizontal>
                                                <b-input v-model="user.website"></b-input>
                                            </b-field>
                                            <b-field label="Bio"
                                                     :type="{'is-danger': hasError('bio')}"
                                                     :message="getError('bio')"
                                                     horizontal>
                                                <b-input v-model="user.bio" type="textarea" maxlength="400"></b-input>
                                            </b-field>
                                            <b-field label="Email"
                                                     :type="{'is-danger': hasError('email')}"
                                                     :message="getError('email')"
                                                     horizontal>
                                                <b-input v-model="user.email"></b-input>
                                            </b-field>
                                            <b-field label="Phone Number"
                                                     :type="{'is-danger': hasError('phone_number')}"
                                                     :message="getError('phone_number')"
                                                     horizontal>
                                                <b-input v-model="user.phone_number"></b-input>
                                            </b-field>
                                            <b-field class="mt-30" horizontal>
                                                <b-button :loading="loading" type="is-primary"
                                                          @click.prevent="saveData">
                                                    Save
                                                </b-button>
                                            </b-field>
                                        </div>
                                    </div>
                                </article>
                            </b-tab-item>
                            <b-tab-item label="Change password">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content">
                                            <b-field label="Old Password"
                                                     :type="{'is-danger': hasError('old_password')}"
                                                     :message="getError('old_password')"
                                                     horizontal>
                                                <b-input v-model="password.old_password" type="password"
                                                         autocomplete="off"></b-input>
                                            </b-field>
                                            <b-field label="New Password"
                                                     :type="{'is-danger': hasError('new_password')}"
                                                     :message="getError('new_password')"
                                                     horizontal>
                                                <b-input v-model="password.new_password" type="password"
                                                         autocomplete="off"></b-input>
                                            </b-field>
                                            <b-field label="Confirm New Password"
                                                     :type="{'is-danger': hasError('new_password_confirmation')}"
                                                     :message="getError('new_password_confirmation')"
                                                     horizontal>
                                                <b-input v-model="password.new_password_confirmation" type="password"
                                                         autocomplete="off"></b-input>
                                            </b-field>
                                            <b-field class="mt-30" horizontal>
                                                <b-button :loading="loading" type="is-primary"
                                                          @click.prevent="changePassword">
                                                    Change Password
                                                </b-button>
                                            </b-field>
                                        </div>
                                    </div>
                                </article>
                            </b-tab-item>

                            <b-tab-item label="Privacy">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content p-10">
                                            <b-field label="Who can view your profile?">
                                                <b-select v-model="user.privacy">
                                                    <option value="public">Everyone</option>
                                                    <option value="private">Followers</option>
                                                </b-select>
                                            </b-field>
                                            <b-field class="mt-30">
                                                <b-button :loading="loading" type="is-primary"
                                                          @click.prevent="changePrivacy">
                                                    Save
                                                </b-button>
                                            </b-field>
                                        </div>
                                    </div>
                                </article>
                            </b-tab-item>
                        </b-tabs>
                    </section>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from "../../layouts/AppLayout";
    import BField from "buefy/src/components/field/Field";

    import {mapGetters, mapActions} from 'vuex';
    import {CircleStencil} from "vue-advanced-cropper";

    import {ErrorsMixin} from '@/mixins/ErrorsMixin';

    export default {
        name: "SettingsComponent",
        components: {CircleStencil, BField, AppLayout},
        mixins: [ErrorsMixin],
        data() {
            return {
                user: {},
                password: {
                    old_password: null,
                    new_password: null,
                    new_password_confirmation: null
                },
                loading: false,
                previewAvatar: null
            }
        },
        created() {
            this.GET_AUTH_USER().then(r => {
                this.user = Object.assign({}, r.auth);
                delete this.user.avatar;
            });
        },
        computed: {
            ...mapGetters('user', ['AUTH_USER']),
        },
        watch: {
            'user.avatar': function (file) {
                let self = this;
                let reader = new FileReader();
                reader.onload = function (e) {
                    self.previewAvatar = e.target.result;
                };
                reader.readAsDataURL(file);
                this.saveData();
            }
        },
        methods: {
            ...mapActions('user', ['GET_AUTH_USER', 'SAVE_AUTH_USER_DATA', 'CHANGE_PASSWORD', 'CHANGE_PRIVACY']),
            saveData() {
                let self = this;
                this.loading = true;
                this.errors = [];

                let formData = new FormData();
                Object.keys(self.user).forEach(function (key) {
                    if (self.user[key] !== null) {
                        formData.append(key, self.user[key]);
                    }
                });

                this.SAVE_AUTH_USER_DATA(formData).then(() => {
                    this.$buefy.notification.open({
                        message: 'Profile saved successfully!',
                        type: 'is-success'
                    });
                    this.loading = false;
                    this.previewAvatar = null;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    this.loading = false;
                })
            },
            changePassword() {
                let self = this;
                self.loading = true;
                self.errors = [];

                this.CHANGE_PASSWORD(self.password).then(() => {
                    this.$buefy.notification.open({
                        message: 'Password changed successfully!',
                        type: 'is-success'
                    });
                    this.loading = false;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    this.loading = false;
                })
            },
            changePrivacy() {
                let self = this;
                self.loading = true;
                self.errors = [];

                this.CHANGE_PRIVACY(self.user.privacy).then(() => {
                    this.$buefy.notification.open({
                        message: 'Privacy changed successfully!',
                        type: 'is-success'
                    });
                    this.loading = false;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    this.loading = false;
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .field {
        &.profile-photo {
            figure {
                margin-left: 80px;
            }
        }
    }
</style>