<template>
    <AuthLayout>
        <section class="hero is-fullheight">
            <div class="hero-body">
                <div class="container">
                    <div class="columns is-marginless is-centered">
                        <div class="column is-7 is-grouped-centered">
                            <img src="/images/logo144x144.png" width="104" alt="">
                            <h1 class="title is-2 mb-50">Welcome to PhotoLife</h1>
                            <div class="columns features mt-50 is-hidden-mobile">
                                <div class="column is-4">
                                    <div class="card">
                                        <b-icon icon="heart" type="is-danger"></b-icon>
                                        <div class="card-header-title is-centered">
                                            <h2 class="subtitle">LIKE</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="card">
                                        <b-icon icon="account-plus" type="is-primary"></b-icon>
                                        <div class="card-header-title is-centered">
                                            <h2 class="subtitle">FOLLOW</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="card">
                                        <b-icon icon="bookmark-multiple" type="is-info"></b-icon>
                                        <div class="card-header-title is-centered">
                                            <h2 class="subtitle">SAVE</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="columns features mt-20 is-hidden-mobile">
                                <div class="column is-4">
                                    <div class="card">
                                        <b-icon icon="message" type="is-danger"></b-icon>
                                        <div class="card-header-title is-centered">
                                            <h2 class="subtitle">MESSENGER</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="card">
                                        <b-icon icon="account-search" type="is-primary"></b-icon>
                                        <div class="card-header-title is-centered">
                                            <h5 class="subtitle">SEARCH</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4">
                                    <div class="card">
                                        <b-icon icon="star" type="is-info"></b-icon>
                                        <div class="card-header-title is-centered">
                                            <h2 class="subtitle">POPULARITY</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-5">
                            <div class="card" v-if="showLoginForm">
                                <header class="card-header">
                                    <h2 class="subtitle is-4 card-header-title">Sign In</h2>
                                </header>

                                <div class="card-content">
                                    <form class="login-form" method="POST" action="#" @submit="login">
                                        <b-field :type="{'is-danger': hasError('username')}"
                                                 :message="getError('username')">
                                            <b-input icon="account-circle" placeholder="Username or Email"
                                                     rounded v-model="userDetails.username"></b-input>
                                        </b-field>
                                        <b-field :type="{'is-danger': hasError('password')}"
                                                 :message="getError('password')">
                                            <b-input type="password" icon="textbox-password" placeholder="Password"
                                                     rounded v-model="userDetails.password"></b-input>
                                        </b-field>
                                        <div class="field is-horizontal mt-20">
                                            <div class="field-body">
                                                <div class="field is-grouped">
                                                    <div class="control">
                                                        <a @click.prevent="showLoginForm = false">
                                                            Forgot Your Password?
                                                        </a>
                                                    </div>
                                                    <div class="control is-end">
                                                        <a href="">
                                                            <i class="fab fa-facebook"></i>
                                                        </a>
                                                        <a href="">
                                                            <i class="fab fa-instagram"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="is-centered buttons mt-20">
                                            <b-button type="is-primary" native-type="submit" class="button-login" :loading="loading">
                                                Login
                                            </b-button>
                                        </div>
                                        <div class="has-text-centered has-text-dark has-text-weight-bold">
                                            OR
                                        </div>
                                        <div class="is-centered buttons mt-20">
                                            <b-button type="is-success is-outlined" tag="router-link"
                                                      :to="{name: 'register'}">
                                                Sign Up
                                            </b-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card" v-else>
                                <header class="card-header">

                                    <h2 class="card-header-title is-centered subtitle is-4 card-header-title">
                                        <a @click.prevent="showLoginForm = true" class="mr-10">
                                            <b-icon icon="arrow-left-circle"></b-icon>
                                        </a>
                                        Trouble Logging In?
                                    </h2>
                                </header>

                                <div class="card-content">
                                    <div class="lock-icon is-centered">
                                        <b-icon icon="account-lock" size="is-large"></b-icon>
                                    </div>
                                    <div class="mb-15">Enter your username or email and we'll send you a link to get
                                        back
                                        into your account.
                                    </div>
                                    <form class="login-form" method="POST" action="#" @submit="sendResetLinkEmail">
                                        <b-field :type="{'is-danger': hasError('email')}"
                                                 :message="getError('email')">
                                            <b-input icon="account-circle" placeholder="Email"
                                                     rounded v-model="forgot_email"></b-input>
                                        </b-field>
                                        <div class="is-centered buttons mt-20">
                                            <b-button type="is-primary" native-type="submit" class="button-login">
                                                Send Login Link
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
        name: "LoginComponent",
        components: {BIcon, AuthLayout},
        mixins: [ErrorsMixin],
        data() {
            return {
                loading: false,
                userDetails: {
                    username: '',
                    password: ''
                },
                forgot_email: '',
                showLoginForm: true
            }
        },
        methods: {
            ...mapActions('user', ['LOGIN', 'SEND_RESET_LINK_EMAIL']),
            login(e) {
                this.loading = true;
                this.LOGIN(this.userDetails).then(r => {
                    this.loading = false;
                    window.location.reload();
                }).catch(e => {
                    if (e.response.status === 422) {
                        this.loading = false;
                        this.errors = e.response.data.errors;
                    }
                });

                return e.preventDefault();
            },
            sendResetLinkEmail(e) {
                this.SEND_RESET_LINK_EMAIL({email: this.forgot_email}).then(r => {

                }).catch(e => {
                    if (e.response.status === 422) {
                        this.errors = e.response.data.errors;
                    }
                });

                return e.preventDefault();
            }
        }
    }
</script>

<style scoped lang="scss">
    .lock-icon {
        width: 100px;
        height: 100px;
        line-height: 100px;
        text-align: center;
        border: 1px solid;
        border-radius: 100%;
        margin: 10px auto 20px auto;
    }
</style>