<template>
    <div class="wrapper">
        <ValidationObserver class="login" :class="[loading ? 'loading' : '', formOk ? 'ok': '', formError ? 'error' : '']" ref="refRegister" v-slot="{ invalid }">
            <form @submit.prevent="onSubmit">
                <h1 class="title">Finalizar registro</h1>
                <ValidationProvider name="Contraseña" rules="required|min:8" v-slot="{ errors }">
                    <input type="password" placeholder="Contraseña" v-model="password" />
                    <i class="fa fa-key"></i>
                    <span class="msg-error" v-show="errors[0]"><i class="fa fa-close"></i>{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider name="Confirmación de Contraseña" rules="required|min:8|password:@Contraseña" v-slot="{ errors }">
                    <input type="password" placeholder="Confirme Contraseña" v-model="confirm" />
                    <i class="fa fa-key"></i>
                    <span class="msg-error" v-show="errors[0]"><i class="fa fa-close"></i>{{ errors[0] }}</span>
                </ValidationProvider>
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
    import {mapMutations} from 'vuex';
    export default {
        name: "register-second",
        data() {
            return {
                loading: false,
                formOk: false,
                formError: false,
                textButton: 'Registrar',
            }
        },
        props: [
            'paramtoken'
        ],
        computed: {
            token: {
                get: function() {
                    return this.$store.state.Register.token;
                }
            },
            password: {
                get: function() {
                    return this.$store.state.Register.password;
                },
                set: function ( newPassword ) {
                    this.$store.state.Register.password = newPassword;
                }
            },
            confirm: {
                get: function() {
                    return this.$store.state.Register.confirm;
                },
                set: function ( newPassword ) {
                    this.$store.state.Register.confirm = newPassword;
                }
            }
        },
        methods: {
            ...mapMutations(['SET_TOKEN']),
            onSubmit() {
                let me = this;
                me.$refs.refRegister.validate().then(success => {
                    if( !success ) {
                        return;
                    }
                    me.SET_TOKEN( me.paramtoken );
                    me.loading = true;
                    me.textButton = 'Validando...';
                    me.$store.dispatch('registerSecondStep').then( response => {
                        if( response.status ) {
                            setTimeout( function() {
                                me.formOk = true;
                                me.textButton = response.msg;
                                setTimeout( function() {
                                    me.formOk = false;
                                    me.textButton = 'Registrar';
                                    me.loading = false;
                                }, 2500);
                            }, 1500);
                        } else {
                            setTimeout( function() {
                                me.formError = true;
                                me.textButton = response.msg;
                                setTimeout(function () {
                                    me.textButton = 'Registrar';
                                    me.loading = false;
                                    me.formError = false;
                                }, 2500);
                            }, 1500);
                        }
                    });
                });
            }
        }
    }
</script>

<style scoped>

</style>
