<template>
    <div class="wrapper">
        <ValidationObserver class="login" :class="[loading ? 'loading' : '', formOk ? 'ok': '', formError ? 'error' : '']" ref="refRegister" v-slot="{ invalid }">
            <form @submit.prevent="onSubmit">
                <h1 class="title">Registro</h1>
                <div class="content-input">
                    <select v-model="typeDocument">
                        <option value="1">DNI</option>
                        <option value="2">RUC</option>
                    </select>
                </div>
                <ValidationProvider class="content-input" name="Documento" :rules="parseInt(typeDocument) === 1 ? 'required|digits:8' : 'required|digits:11'" v-slot="{ errors }">
                    <input type="text" placeholder="Nro de doc." v-model="document"/>
                    <i class="fa fa-address-card"></i>
                    <span class="msg-error" v-show="errors[0]"><i class="fa fa-close"></i>{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider class="content-input" name="Correo Electrónico" rules="required|email" v-slot="{ errors }">
                    <input type="email" placeholder="Correo Electrónico" v-model="email"/>
                    <i class="fa fa-envelope"></i>
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

    export default {
        name: "register",
        data() {
            return {
                loading: false,
                formOk: false,
                formError: false,
                textButton: 'Registrar',
            }
        },
        computed: {
            email: {
                get: function() {
                    return this.$store.state.Register.email;
                },
                set: function ( newEmail ) {
                    this.$store.state.Register.email = newEmail;
                }
            },
            typeDocument: {
                get: function() {
                    return this.$store.state.Register.typeDocument;
                },
                set: function ( newType ) {
                    this.$store.state.Register.typeDocument = newType;
                }
            },
            document: {
                get: function() {
                    return this.$store.state.Register.document;
                },
                set: function ( newDocument ) {
                    this.$store.state.Register.document = newDocument;
                }
            },
        },
        methods: {
            onSubmit() {
                let me = this;
                this.$refs.refRegister.validate().then(success => {
                    if( !success ) {
                        return;
                    }
                    me.loading = true;
                    me.textButton = 'Validando...';

                    this.$store.dispatch('registerFirstStep').then( response => {
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
                    }).catch( errors => {
                        setTimeout( function() {
                            me.formError = true;
                            me.textButton = 'Ocurrio un problema, comuniquese con su administrador.';
                            setTimeout(function () {
                                me.textButton = 'Registrar';
                                me.loading = false;
                                me.formError = false;
                            }, 2500);
                        }, 1500);
                    });

                    me.$nextTick(() => {
                        me.$refs.refRegister.reset();
                    });

                });
            }
        }
    }
</script>

<style scoped>

</style>
