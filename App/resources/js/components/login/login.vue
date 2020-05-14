<template>
    <div class="wrapper">
        <ValidationObserver class="login" :class="[loading ? 'loading' : '', loginOk ? 'ok': '', loginError ? 'error' : '']" ref="refLogin" v-slot="{ invalid }">
            <form @submit.prevent="onSubmit">
                <h1 class="title">Bienvenido de nuevo</h1>
                <ValidationProvider name="Correo Electrónico" rules="required|email" v-slot="{ errors }">
                    <input type="email" placeholder="Correo Electrónico" v-model="email" autofocus/>
                    <i class="fa fa-user"></i>
                    <span class="msg-error" v-show="errors[0]"><i class="fa fa-close"></i>{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider name="Contraseña" rules="required|min:8" v-slot="{ errors }">
                    <input type="password" placeholder="Contraseña" v-model="password" />
                    <i class="fa fa-key"></i>
                    <span class="msg-error" v-show="errors[0]"><i class="fa fa-close"></i>{{ errors[0] }}</span>
                </ValidationProvider>
                <div class="w-100">
                    <a href="#" class="link">¿Olvidaste tu contraseña?</a>
                </div>
                <button type="submit" :disabled="invalid">
                    <i class="spinner"></i>
                    <span class="state">{{ textButton }}</span>
                </button>
            </form>
        </ValidationObserver>
<!--        <footer><a target="blank" href="http://boudra.me/">boudra.me</a></footer>-->
    </div>
</template>

<script>
    export default {
        name: "login",
        data() {
            return {
                loading: false,
                loginOk: false,
                loginError: false,
                textButton: 'Iniciar Sesión'
            }
        },
        computed: {
            email: {
                get: function() {
                    return this.$store.state.User.email;
                },
                set: function ( newEmail ) {
                    this.$store.state.User.email = newEmail;
                }
            },
            password: {
                get: function() {
                    return this.$store.state.User.password;
                },
                set: function ( newPassword ) {
                    this.$store.state.User.password = newPassword;
                }
            }
        },
        methods: {
            onSubmit() {
                let me = this;
                this.$refs.refLogin.validate().then(success => {
                    if( !success ) {
                        return;
                    }
                    me.loading = true;
                    me.textButton = 'Validando...'

                    this.$store.dispatch('login').then( response => {
                        if( response.status ) {
                            setTimeout( function() {
                                me.loginOk = true;
                                me.textButton = response.msg;
                                setTimeout( function() {
                                    me.dashboard();
                                }, 1500);
                            }, 1500);

                        } else {
                            setTimeout( function() {
                                me.loginError = true;
                                me.textButton = response.msg;
                                setTimeout(function () {
                                    me.textButton = 'Iniciar Sesión';
                                    me.loading = false;
                                    me.loginError = false;
                                }, 2500);
                            }, 1500 );
                        }
                    });

                    this.$nextTick(() => {
                        this.$refs.refLogin.reset();
                    });
                });
            },
            dashboard() {
                location.href = '/dashboard';
            }
        }
    }
</script>

<style scoped>

</style>
