<template>
    <div class="products">
        <section class="page-search">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Advance Search -->
                        <div class="advance-search">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-10">
                                        <input type="text" class="form-control my-2 my-lg-0" id="inputtext4" placeholder="¿Qué estás buscando?">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="submit" class="btn btn-custom">Buscar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-sm">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="search-result bg-gray">
                            <h2 class="top" v-if="resultSearch !== ''">Resultados para "Electrónica"</h2>
                            <p class=""><strong>{{ pagination.total }}</strong> Resultados encontrados.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="category-sidebar">
                            <div class="widget category-list" v-if="categories.length > 0">
                                <h4 class="widget-header">Categorías</h4>
                                <ul class="category-list">
                                    <li v-for="cat in categories" :key="cat.id">
                                        <a href="javascript:">{{ cat.name }} <span>{{ cat.count}}</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="widget category-list" v-if="brands.length > 0">
                                <h4 class="widget-header">Marcas</h4>
                                <ul class="category-list">
                                    <li v-for="br in brands" :key="br.id">
                                        <a href="javascript:">{{ br.name }} <span>{{ br.count }}</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="widget filter">
                                <h4 class="widget-header">Mostrar Productos</h4>
                                <select style="display: none;">
                                    <option>Más búscados</option>
                                    <option value="1">Los más valorados</option>
                                    <option value="2">Precio más bajo</option>
                                    <option value="3">Precio más alto</option>
                                </select><div class="nice-select" tabindex="0"><span class="current">Popularity</span><ul class="list"><li data-value="Popularity" class="option selected">Popularity</li><li data-value="1" class="option">Top rated</li><li data-value="2" class="option">Lowest Price</li><li data-value="4" class="option">Highest Price</li></ul></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="category-search-filter">
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>Short</strong>
                                    <select style="display: none;">
                                        <option>Más reciente</option>
                                        <option value="1">Los más valorados</option>
                                        <option value="2">Precio más bajo</option>
                                        <option value="3">Precio más alto</option>
                                    </select><div class="nice-select" tabindex="0"><span class="current">Most Recent</span><ul class="list"><li data-value="Most Recent" class="option selected focus">Most Recent</li><li data-value="1" class="option">Most Popular</li><li data-value="2" class="option">Lowest Price</li><li data-value="4" class="option">Highest Price</li></ul></div>
                                </div>
<!--                                <div class="col-md-6">-->
<!--                                    <div class="view">-->
<!--                                        <strong>Views</strong>-->
<!--                                        <ul class="list-inline view-switcher">-->
<!--                                            <li class="list-inline-item">-->
<!--                                                <a href="category.html"><i class="fa fa-th-large"></i></a>-->
<!--                                            </li>-->
<!--                                            <li class="list-inline-item">-->
<!--                                                <a href="category.html" class="text-info"><i class="fa fa-reorder"></i></a>-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                        </div>

                        <!-- ad listing list  -->
                        <div class="ad-listing-list mt-20" v-for="pr in products" :key="pr.id">
                            <div class="row p-lg-3 p-sm-5 p-4">
                                <div class="col-lg-4 align-self-center">
                                    <a :href="pr.detail">
                                        <img v-if="pr.images.length > 0" :src="admin_url + 'uploads/presentations/' + pr.id + '/' + pr.images[0].image_original" class="img-fluid" :alt="pr.name">
                                        <img v-else src="/images/theme/not-image.jpg" class="img-fluid" :alt="pr.name">
                                    </a>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-10">
                                            <div class="ad-listing-content">
                                                <div>
                                                    <a :href="pr.detail" class="font-weight-bold">{{ pr.name }}</a>
                                                </div>
                                                <ul class="list-inline mt-2 mb-3">
                                                    <li class="list-inline-item">
                                                        <a :href="pr.detail">
                                                            <i class="fa fa-barcode"></i> {{ pr.sku }}
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a :href="pr.detail">
                                                            <i class="fa fa-folder-open-o"></i> {{ pr.category.name }}
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item" v-if="pr.brand.name !== ''">
                                                        <a :href="pr.detail">
                                                            <i class="fa fa-codepen"></i>{{ pr.brand.name }}
                                                        </a>
                                                    </li>
                                                </ul>
                                                <p class="pr-5">{{ pr.description }}</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 align-self-center">
                                            <div class="product-ratings float-lg-right pb-3">
                                                <ul class="list-inline">
                                                    <li v-for="i in 5" class="list-inline-item" :class="pr.rating >= i ? 'selected': ''">
                                                        <i class="fa fa-star"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ad listing list  -->

                        <!-- pagination -->
                        <div class="pagination justify-content-center py-4">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">«</span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Next">
                                            <span aria-hidden="true">»</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <!-- pagination -->
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import {mapMutations} from 'vuex';
    export default {
        name: "ProductsIndex",
        data() {
            return {
                admin_url: ADMIN_URL
            }
        },
        created() {
            this.$store.dispatch( 'loadProductsV2' );
        },
        methods: {
            ...mapMutations(['CHANGE_PAGE']),
            list( page = 1 ) {
                this.CHANGE_PAGE( page );
                this.$store.dispatch( 'loadProductsV2' );
            }
        },
        computed: {
            products: {
                get: function () {
                    return this.$store.state.Products.products;
                },
                set: function ( products ) {
                    this.$store.state.Products.products = products;
                }
            },
            resultSearch: {
                get: function () {
                    return this.$store.state.Products.resultSearch;
                }
            },
            pagination: {
                get: function () {
                    return this.$store.state.Products.pagination;
                },
                set: function ( pagination ) {
                    this.$store.state.Products.pagination = pagination;
                }
            },
            categories: {
                get: function () {
                    return this.$store.state.Products.categories;
                },
                set: function ( categories ) {
                    this.$store.state.Products.categories = categories;
                }
            },
            brands: {
                get: function () {
                    return this.$store.state.Products.brands;
                },
                set: function ( brands ) {
                    this.$store.state.Products.brands = brands;
                }
            }
        }
    }
</script>

<style scoped>

</style>
