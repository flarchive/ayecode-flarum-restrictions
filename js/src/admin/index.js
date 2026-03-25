import app from 'flarum/admin/app';

// ...
app.initializers.add('ayecode-restrictions-admin', () => {
    console.log('AYECODE: Admin initializer');
});
