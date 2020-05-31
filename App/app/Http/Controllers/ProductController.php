<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Presentation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $_meta = [];
    protected $_metaTagSocials = [];
    protected $_title = 'Productos';
    protected $_description = 'Siempre contamos con los productos de la mejor calidad.';

    public function __construct()
    {
        $this->_meta = [
            'key' => 'description',
            'value' => $this->_description
        ];

        $this->setTitle( $this->_title );
        $this->setMetaTags( $this->_meta );
    }

    public function index( Request $request ) {
        $brands = [];
        $brandrecords = Brand::where('status', 1)->whereHas( 'presentations', function( $q ) {
            $q->where( 'status', 1 )
                ->whereNotNull( 'products_id' );
        })->orderBy( 'name', 'asc' )->get();

        foreach ( $brandrecords as $brandrecord ) {
            $row = new \stdClass();
            $row->id = $brandrecord->id;
            $row->name = $brandrecord->name;
            $row->count = $brandrecord->presentations->where( 'status', 1 )->where( 'products_id', '>', 0 )->count();

            $brands[] = $row;
        }

        $categories = [];
        $categoryRecords = Category::where( 'status', 1 )
            ->whereHas( 'products', function ( $q ) {
                $q->where( 'status', 1 )->whereHas( 'presentations', function( $q ) {
                    $q->where( 'status', 1 );
                });
            })->orderBy( 'name', 'ASC' )->get();

        foreach ( $categoryRecords as $categoryRecord ) {

            $products = $categoryRecord->products;
            foreach ( $products as $product ) {
                $count = $product->presentations->where('status', 1 )->count();
                if( $count > 0 ) {
                    $key = array_search( $categoryRecord->id, array_column( $categories, 'id'));
                    if( is_bool( $key ) ) {
                        $categories[] = [
                            'id' => $categoryRecord->id,
                            'name' => $categoryRecord->name,
                            'count' => $count
                        ];
                    } else {
                        $categories[$key]['count'] += $count;
                    }
                }
            }
        }

        $presentations = [];
        $records = Presentation::where( 'status', 1 )
            ->whereNotNull( 'products_id' )
            ->paginate( self::PAGINATE );

        foreach ( $records as $record ) {

            $product = $record->product;
            $brand = $record->brand;

            $row = new \stdClass();
            $row->id = $record->id;
            $row->sku = $record->sku;
            $row->name = $record->description;
            $row->description = $product->description;
            $row->detail = route( 'products.detail', ['slug' => $record->slug]);
            $row->rating = rand( 1, 5 );
            $row->brand = [
                'id' => $brand ? $brand->id : 0,
                'name' => $brand ? $brand->name : ''
            ];
            $row->category = [
                'id' => $product->category ? $product->category->id : 0,
                'name' => $product->category ? $product->category->name : '',
            ];
            $presentations[] = $row;
        }

        $response = [
            'status' => true,
            'presentations' => $presentations,
            'brands' => $brands,
            'categories' => $categories,
            'pagination' => [
                'total' => $records->total(),
                'current_page' => $records->currentPage(),
                'per_page' => $records->perPage(),
                'last_page' => $records->lastPage(),
                'from' => $records->firstItem(),
                'to' => $records->lastItem()
            ],
        ];

        return response()->json( $response, 200 );
    }

    public function dashboard() {
        $this->initialMetaTagSocial();
        $this->setMetaTagSocial( $this->_metaTagSocials );

        $data = [
            'activeSide' => 'contact-us',
            'title' => $this->title,
            'metaTags' => $this->metaTags,
            'metaTagSocials' => $this->metaTagSocials
        ];

        return view('classimax.pages.products', $data);
    }

    private function initialMetaTagSocial() {
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'fb:app_id', 'content' => env('FACEBOOK_APP_ID')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:url', 'content' => route('contact-us')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:description', 'content' => $this->_description];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];

        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:url', 'content' => route('contact-us')];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:description', 'content' => $this->_description];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];
    }

    public function getProducts() {

        $PresentationModel = new Presentation();
        $products = $PresentationModel->listData();

        return response()->json( [ 'products' => $products ] );
    }

    public function detail( Request $request ) {

    }
}
