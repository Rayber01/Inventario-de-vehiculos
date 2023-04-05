<template>
  <v-app theme="dark">
    <Navbar
      :drawer-visible="drawerVisible"
      @toggle-drawer="toggleDrawer"
    />
    <v-app-bar>
      <v-app-bar-nav-icon @click.stop="toggleDrawer" />
      <v-toolbar-title>
        <b>{{ titulo }}</b>
      </v-toolbar-title>
      <v-spacer />
      <v-text-field
        v-if="searchVisible"
        v-model="searchTerm"
        class="mt-5 mr-3"
        label="Buscar"
        clearable
        variant="solo"
        prepend-icon="mdi-magnify"
        @keyup.enter="search"
        @keyup.esc="searchVisible = false"
        @click:clear="onClear"
      />
      <v-btn 
        v-else
        variant="text"
        icon="mdi-magnify"
        @click="searchVisible = true"
      />
    </v-app-bar>
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
export default {
	data() {
		return {
			drawerVisible: false,
			searchVisible: false,
			searchTerm: ''
		}
	},
	computed: {
		titulo() {
			return this.$route.name
		}
	},
	watch: {
		'$route'() {
			document.title = this.$route.meta.title
		}
	},
	methods: {
		toggleDrawer() {
			this.drawerVisible = !this.drawerVisible
		},
		async search() {
			if(this.searchTerm === ''){
				this.$router.push({
					path: '/filter',
					query: {
						allCars: true
					}
				})
			} else {
				this.$router.push({
					path: '/filter',
					query: {
						key: this.searchTerm
					}
				})
			}

		},
		onClear() {
			if(this.$route.name === 'Filtro'){
				this.$router.push({
					path: '/filter',
					query: {
						allCars: true
					}
				})
			}
		}
	}
}
</script>