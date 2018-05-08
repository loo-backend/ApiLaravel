<?php

use Illuminate\Database\Seeder;

class PedidoCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pedidos = factory(App\Produto::class, 5)->create();
        $pedidos->each(function ($pedido) {
            factory(App\Pedido::class, rand(1,2))->create([
                'produto_id' => $pedido->id
            ]);
        });
    }
}
