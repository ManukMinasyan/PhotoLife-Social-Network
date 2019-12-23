<template>
    <AuthLayout>
        <section class="hero is-fullheight">
            <div class="hero-body">
                <div class="container">
                    <div class="columns is-marginless is-centered">
                        <div class="column is-6">
                            <div class="column">
                                <router-link :to="{name: 'login'}" class="button is-small">
                                    <b-icon icon="arrow-left-circle" class="mr-5"></b-icon>
                                    Sign In
                                </router-link>
                                <div class="mt-30">
                                    <h1 class="title is-3">Let's get you set up!</h1>
                                    <h3 class="title is-6 mb-50">
                                        Sign up to see photos and videos from your friends.
                                    </h3>
                                    <img src="/images/logo144x144.png" width="144" alt="">
                                    <span class="has-text-info" style="font-size: 50pt">+</span>
                                    <span class="title is-2 has-text-primary">
                                        <b-icon icon="account-group" size="is-large"></b-icon>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="column is-7">
                            <div class="card mt-50">
                                <header class="card-header">
                                    <h2 class="subtitle is-4 card-header-title">Register</h2>
                                </header>

                                <div class="card-content">
                                    <form class="register-form" method="POST" action="#" @submit="register">
                                        <b-field :type="{'is-danger': hasError('username')}"
                                                 :message="getError('username')" label="Username" horizontal>
                                            <b-input v-model="userDetails.username" expanded></b-input>
                                        </b-field>

                                        <b-field :type="{'is-danger': hasError('email')}"
                                                 :message="getError('email')" label="E-mail" horizontal>
                                            <b-input v-model="userDetails.email" expanded></b-input>
                                        </b-field>

                                        <b-field :type="{'is-danger': hasError('password')}"
                                                 :message="getError('password')" label="Password" horizontal>
                                            <b-input type="password" v-model="userDetails.password"  expanded password-reveal></b-input>
                                        </b-field>

                                        <b-field :type="{'is-danger': hasError('confirm_password')}"
                                                 :message="getError('confirm_password')" label="Confirm Password"
                                                 horizontal>
                                            <b-input type="password" v-model="userDetails.password_confirmation" expanded password-reveal></b-input>
                                        </b-field>

                                        <div class="is-centered buttons mt-20">
                                            <b-button type="is-primary" native-type="submit" :loading="loading">
                                                Register
                                            </b-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthLayout>
</template>

<script>
    import AuthLayout from "../../layouts/AuthLayout";
    import BIcon from "buefy/src/components/icon/Icon";

    import {mapActions} from 'vuex';

    import {ErrorsMixin} from '@/mixins/ErrorsMixin';

    export default {
        name: "RegisterComponent",
        components: {BIcon, AuthLayout},
        mixins: [ErrorsMixin],
        data() {
            return {
                loading: false,
                userDetails: {
                    username: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        },
        methods: {
            ...mapActions('user', ['REGISTER']),
            register(e) {
                this.loading = true;
                this.REGISTER(this.userDetails).then(r => {
                    this.loading = false;
                    window.location.reload();
                }).catch(e => {
                    this.loading = false;
                    if (e.response.status === 422) {
                        this.errors = e.response.data.errors;
                    }
                });

                return e.preventDefault();
            }
        }
    }
</script>

<style scoped>

</style>