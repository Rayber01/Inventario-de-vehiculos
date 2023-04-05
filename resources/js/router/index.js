import { createRouter, createWebHistory } from 'vue-router'

const InventarioComponent = () => import ('../web/InventarioCom.vue')
const AgregarComponent = () => import ('../web/AgregarCom.vue')
const VerVehiculoComponent = () => import ('../web/VerVehiculoCom.vue')
const EditarVehiculoComponent = () => import ('../web/EditarVehiculoCom.vue')
const FiltroComponent = () => import ('../web/FiltroCom.vue')

const routes = [
	{ path: '/inventario', 
		name: 'Inventario',
		component: InventarioComponent,
		meta: { title: 'Inventario' }
	},
	{
		path: '/agregar',
		name: 'Agregar vehículo',
		component: AgregarComponent,
		meta: { title: 'Agregar vehículo' }
	},
	{
		path: '/ver/vehiculo/:id',
		name: 'Ver vehículo',
		component: VerVehiculoComponent,
		meta: { title: 'Ver vehículo' }
	},
	{
		path: '/editar/vehiculo/:id',
		name: 'Editar vehículo',
		component: EditarVehiculoComponent,
		meta: { title: 'Editar vehículo' }
	},
	{
		path: '/filter',
		name: 'Filtro',
		component: FiltroComponent,
		meta: { title: 'Filtro' }
	}
]

const router = createRouter({
	history: createWebHistory(),
	routes
})
export default router