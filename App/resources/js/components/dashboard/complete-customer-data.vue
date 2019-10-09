<template>
    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
        <div class="widget personal-info">
            <h3 class="widget-header user">Información de cliente</h3>
            <ValidationObserver ref="completeCustomer" v-slot="{ invalid }">
                <form action="#">
                    <div class="form-group row margin-top-2-por">
                        <label class="col-md-3 form-control-label">Tipo de Persona</label>
                        <div class="form-check form-check-inline" v-for="tper in arrTypePerson" :key="tper.id">
                            <input type="radio" class="form-check-input" :id="'typedoc' + tper.id" :name="'typedoc' + tper.id" :value="tper.id" v-model="typePerson">
                            <label class="form-check-label" :for="'typedoc' + tper.id" v-text="tper.name"></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            <template v-if="typePerson == 1">Nombre</template>
                            <template v-else>Razón Social</template>
                            <span class="text-danger">(*)</span>
                        </label>
                        <div :class="typePerson === 1 ? 'col-md-4' : 'col-md-9'">
                            <ValidationProvider name="nombre" rules="required" v-slot="{ errors }">
                                <input type="text" v-model="name" class="form-control" :placeholder="typePerson == 1 ? 'Nombre' : 'Razón social'">
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>

                        </div>
                        <div class="col-md-5" v-show="typePerson === 1">
                            <ValidationProvider name="apellidos" :rules="typePerson === 1 ? 'required' : ''" v-slot="{ errors }">
                                <input type="text" v-model="lastname" class="form-control" placeholder="Apellidos">
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                    <div class="form-group row" v-show="typePerson === 2">
                        <label class="col-md-3 form-control-label">Nombre comercial</label>
                        <div class="col-md-9">
                            <input type="text" v-model="businessName" name="nombre_comercial" class="form-control" placeholder="Nombre comercial">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Nro de doc. <span class="text-danger">(*)</span></label>
                        <div class="col-md-3">
                            <ValidationProvider name="Tipo de doc." rules="required" v-slot="{ errors }">
                                <select class="form-control" v-model="typeDocument">
                                    <option value="" disabled selected hidden >Tipo de doc</option>
                                    <option v-for="td in arrTypeDocuments" v-bind:value="td.id" v-text="td.name"></option>
                                </select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="col-md-6">
                            <ValidationProvider name="documento" :rules="{ required: true, regex: /^[0-9]+$/, min:8, max:20 }" v-slot="{ errors }">
                                <input type="text" v-model="document" class="form-control" placeholder="Nro. de doc.">
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                    <div class="form-group row" v-show="typePerson == 2">
                        <label class="col-md-3 form-control-label">Representante Legal</label>
                        <div class="col-md-9">
                            <input type="text" v-model="legalRep" name="representante_legal" class="form-control" placeholder="Rep. Legal">
                        </div>
                    </div>
                    <div class="form-group row" v-show="typePerson == 2">
                        <label class="col-md-3 form-control-label">Nro de doc.</label>
                        <div class="col-md-3">
                            <ValidationProvider name="tipo doc." :rules="( documentLp && documentLp.length > 0 ) ? 'required' : ''" v-slot="{ errors }">
                                <select class="form-control" v-model="typeDocumentLp">
                                    <option value="" disabled selected hidden >Tipo de doc</option>
                                    <option v-for="td in arrTypeDocuments" v-bind:value="td.id" v-text="td.name"></option>
                                </select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="col-md-6">
                            <ValidationProvider name="nro doc." :rules="{ regex: /^[0-9]+$/, min:8, max:20 }" v-slot="{ errors }">
                                <input type="text" v-model="documentLp" name="documento_rep_legal" class="form-control" placeholder="Nro. de doc. rep. legal">
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Dirección <span class="text-danger">(*)</span></label>
                        <div class="col-md-9">
                            <ValidationProvider name="Dirección" rules="required" v-slot="{ errors }">
                                <input type="tex" v-model="address" name="direccion" class="form-control" placeholder="Dirección">
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Ubigeo<span class="text-danger">(*)</span></label>
                        <div class="col-md-3">
                            <ValidationProvider name="departamento" rules="required" v-slot="{ errors }">
                                <select class="form-control" name="departamento" v-model="departamentId"
                                        @change="loadProvince">
                                    <option value="" disabled selected hidden >Departamento</option>
                                    <option v-for="dep in arrDepartaments" v-bind:value="dep.id" v-text="dep.name"></option>
                                </select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="col-md-3">
                            <ValidationProvider name="provincia" rules="required" v-slot="{ errors }">
                                <select class="form-control" name="provincia" v-model="provinceId"
                                        @change="loadDistrict">
                                    <option value="" disabled selected hidden>Provincia</option>
                                    <option v-for="prov in arrProvinces" v-bind:value="prov.id" v-text="prov.name"></option>
                                </select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="col-md-3">
                            <ValidationProvider name="distrito" rules="required" v-slot="{ errors }">
                                <select class="form-control" name="distrito" v-model="districtId">
                                    <option value="" disabled selected hidden>Distrito</option>
                                    <option v-for="dist in arrDistricts" v-bind:value="dist.id" v-text="dist.name"></option>
                                </select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>
                    <button class="btn btn-success text-white" :disabled="invalid" @click.prevent="updateData()">
                        <i class="fa fa-save"></i> Guardar Información
                    </button>
                </form>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
    export default {
        name: "complete-customer-data",
        data() {
            return {
                typePerson: 2,
                name: '',
                lastname: '',
                businessName: '',
                typeDocument: '',
                document: '',
                legalRep: '',
                typeDocumentLp: '',
                documentLp: '',
                address: '',
                departamentId: '',
                provinceId: '',
                districtId: ''
            }
        },
        created() {
            this.$store.dispatch( 'loadDepartaments' );
            this.$store.dispatch( 'loadTypeDocument' );
            this.$store.dispatch( 'loadTypePerson' );
        },
        computed: {
            arrDepartaments() {
                return this.$store.state.StaticData.arrDepartaments;
            },
            arrProvinces() {
                return this.$store.state.StaticData.arrProvinces;
            },
            arrDistricts() {
                return this.$store.state.StaticData.arrDistricts;
            },
            arrTypeDocuments() {
                return this.$store.state.StaticData.arrTypeDocuments;
            },
            arrTypePerson() {
                return this.$store.state.StaticData.arrTypePerson;
            },
        },
        methods: {
            loadProvince() {
                this.$store.dispatch({
                    type: 'loadProvinces',
                    data: {
                        departament: this.departamentId
                    },
                });
            },
            loadDistrict(){
                this.$store.dispatch({
                    type: 'loadDistrict',
                    data: {
                        departament: this.departamentId,
                        province: this.provinceId,
                    },
                });
            },
            updateData() {
                this.$store.dispatch({
                    type: 'Customer/updateCustomer',
                    data: {
                        typePerson: this.typePerson,
                        name: this.name,
                        lastname: this.lastname,
                        businessName: this.businessName,
                        typeDocument: this.typeDocument,
                        document: this.document,
                        legalRep: this.legalRep,
                        typeDocumentLp: this.typeDocumentLp,
                        documentLp: this.documentLp,
                        address: this.address,
                        departamentId: this.departamentId,
                        provinceId: this.provinceId,
                        districtId: this.districtId,
                    }
                }).then( response => {
                    if( response.status ) {
                        this.$store.dispatch( 'loadSettings' );
                    }
                });
            }
        },
        mounted() {
            this.$store.dispatch( 'Customer/loadCustomer' ).then( response => {
                let result = response.data;
                this.typePerson = result.typePerson;
                this.name = result.name;
                this.lastname = result.lastname;
                this.businessName = result.businessName;
                this.typeDocument = result.typeDocument;
                this.document = result.document;
                this.legalRep = result.legalRep;
                this.typeDocumentLp = result.typeDocumentLp;
                this.documentLp = result.documentLp;
                this.address = result.address;
                this.departamentId = result.departamentId;
                this.provinceId = result.provinceId;
                this.districtId = result.districtId;
                this.loadProvince();
                this.loadDistrict();
            }, error => {
                console.log( error );
            });
        }
    }
</script>

<style scoped>

</style>
