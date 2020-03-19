Vue.filter( 'formatPercent', function ( value ) {
    if( ! value ) {
        value = 0;
    }
    let newValue = parseFloat( value );
    return newValue.toFixed( 2 ) + '%';
});
