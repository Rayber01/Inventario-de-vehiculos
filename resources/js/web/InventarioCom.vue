<template>
  <v-container class="pa-4 text-center">
    <v-row
      class="fill-height"
      align="left"
      justify="left"
    >
      <template
        v-for="(item, i) in cars"
        :key="i"
      >
        <v-col
          cols="10"
          md="4"
        >
          <v-hover v-slot="{ isHovering, props }">
            <v-card
              :elevation="isHovering ? 12 : 2"
              :class="{ 'on-hover': isHovering }"
              v-bind="props"
            >
              <v-card-title>
                <b><span>{{ item.financed == true ? 'Financiado' : 'Cash' }}</span> {{ item.sold == true ? '| Vendido' : '' }}</b> <span>{{ item.visible == true ? '|' : '' }}</span> 
                <v-icon
                  v-if="item.visible"
                  icon="mdi-eye"
                  class="ml-1"
                />
              </v-card-title>
              <v-img
                :src="'/storage/app/public/files/'+ item.files"
                height="225px"
                cover
                class="align-self-end"
              >
                <div class="align-self-center">
                  <v-btn
                    variant="text"
                    :class="{ 'show-btns': isHovering }"
                    :color="transparent"
                    icon="mdi-eye"
                    @click="showCar(item.id)"
                  />
                  <v-btn
                    variant="text"
                    :class="{ 'show-btns': isHovering }"
                    :color="transparent"
                    icon="mdi-pencil"
                    @click="editCar(item.id)"
                  />
                  <v-btn
                    variant="text"
                    :class="{ 'show-btns': isHovering }"
                    :color="transparent"
                    icon="mdi-checkbox-marked-circle-outline"
                    @click="showAlertToSold(item.id, item.files)"
                  />
                </div>
              </v-img>
            </v-card>
          </v-hover>
        </v-col>
      </template>
    </v-row>
    <v-pagination
      v-model="pagination.page"
      :length="pagination.lastPage"
      :total-visible="7"
      prev-icon="mdi-chevron-left"
      next-icon="mdi-chevron-right"
      @click.stop="getCars"
    />
  </v-container>
</template>

<script>
import axios from 'axios'
export default {
	data() {
		return {
			transparent: 'rgba(255, 255, 255, 0)',
			cars: [],
			successMessage: '',
			pagination: {
				total: 0,
				page: 1,
				lastPage: 0
			},
			loading: false
		}
	},
	mounted() {
		this.getCars()
	},
	methods: {
		getCars() {
			console.log(this.page)
			this.loading = true
			axios.get(`/cars?page=${this.pagination.page}`).
				then(response => {
					this.cars = response.data.data
					this.pagination.total = response.data.total
					this.pagination.lastPage = response.data.last_page
					this.loading = false
					console.log(response.data)
					if (this.$route.query.success) {
						this.successMessage = this.$route.query.message
						this.showAlert()
					}
				}).
				catch(error => {
					console.log(error)
				})
		},
		showCar(id) {
			this.$router.push('/ver/vehiculo/'+id)
		},
		editCar(id) {
			this.$router.push('/editar/vehiculo/'+id)
		},
		showAlertToSold(id, image){
			this.$swal({
				title: 'Está seguro que quiere marcar este vehículo como vendido?',
				imageUrl: `/storage/app/public/files/${image}`,
				imageWidth: '50%',
				imageHeight: '70%',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Corfirmar',
				cancelButtonText: 'Cancelar',
				backdrop: 'rgba(231, 228, 228,0.4)'
			}).
				then((result) => {
					if (result.isConfirmed) {
						this.markSold(id)
					}
				})
		},
		async markSold(id) {
			try {
				const response = await axios.put(`/car/sold/${id}`)
				this.getCars()
				this.showSoldAlert(response.data.sold)
			} catch(error) {
				console.log(error)
			}
		},
		showSoldAlert(sold) {
			this.$swal({
				toast: true,
				position: 'top-end',
				icon: 'success',
				title: sold === true ? 'Vehículo vendido!' : 'Vehículo a la venta nuevamente!',
				showConfirmButton: false,
				timer: 2000
			})
		},
		showAlert() {
			this.$swal({
				position: 'center',
				icon: 'success',
				title: this.successMessage,
				showConfirmButton: false,
				timer: 3000,
				backdrop: 'rgba(231, 228, 228,0.4)'
			})
		}
	}
}
</script>

<style scoped>
	.v-card {
		transition: opacity .4s ease-in-out;
	}

	.v-card:not(.on-hover) {
		opacity: 0.6;
	}

	.show-btns {
		color: rgba(255, 255, 255, 1) !important;
		margin-top: 170px;
	}
</style>