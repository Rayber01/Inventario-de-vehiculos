<template>
  <v-navigation-drawer v-model="drawerVisible">
    <v-list>
      <v-list-item
        v-for="(item, index) in items"
        :key="index"
        :to="item.ruta"
        :prepend-icon="item.icon"
      >
        <b>{{ item.nombre }}</b>
      </v-list-item>
    </v-list>
    <template #append>
      <div class="pa-2">
        <v-btn
          block
          color="error"
          @click="logout"
        >
          <b>Cerrar sesión</b>
        </v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>

<script>
import axios from 'axios'

export default {
	props: {
		drawerVisible: Boolean
	},
	data () {
		return {
			items: [
				{ nombre: 'Inventario', icon: 'mdi-store', ruta:'/inventario' },
				{ nombre: 'Agregar vehículo', icon: 'mdi-plus', ruta:'/agregar' },
				{ nombre: 'Filtro', icon: 'mdi-filter', ruta:'/filter' }
			]
		}
	},
	methods: {
		logout() {
			axios.post('/logout').
				then(() => {
					window.location.href = '/login'
				}).
				catch(error => {
					console.log(error)
				})
		}
	}
}
</script>