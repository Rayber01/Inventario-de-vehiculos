<template>
  <v-container>
    <v-card>
      <v-card-title class="mt-2">
        <div class="d-flex justify-space-between mb-6">
          <span>Descripción</span>
          <v-btn 
            variant="tonal"
            color="info"
            @click="downloadImages"
          >
            <v-icon left>
              mdi-download
            </v-icon>
            Descargar imagenes
          </v-btn>
        </div>
        <v-divider />
      </v-card-title> 
      <v-card-subtitle>
        <span><b>Tipo:</b> {{ car.financed == true ? 'Financiado' : 'Cash' }}</span>
        <br>
        <span><b>Vendido:</b> {{ car.sold == true ? 'Si' : 'No' }}</span>
        <br>
        <span><b>Visible:</b> {{ car.visible == true ? 'Si' : 'No' }}</span>
        <v-divider />
        <div>
          <v-row>
            <v-col cols="11">
              <v-textarea 
                v-model="car.description" 
                readonly 
                no-resize
              />
            </v-col>
            <v-col cols="1">
              <v-tooltip bottom>
                <template #activator="{ on }">
                  <v-icon
                    class="copy-icon"
                    v-bind="on"
                    @click.stop="copyTextToClipboard()"
                  >
                    mdi-content-copy
                  </v-icon>
                </template>
                <span>Copy to clipboard</span>
              </v-tooltip>
            </v-col>
          </v-row>
          <v-snackbar
            v-model="showSnackbar"
            :timeout="3000"
          >
            Descripción copiada al portapapeles!
            <template #action="{ attrs }">
              <v-btn
                text
                color="green"
                v-bind="attrs"
                @click="showSnackbar = false"
              >
                Cerrar
              </v-btn>
            </template>
          </v-snackbar>
        </div>
      </v-card-subtitle>
      <v-card-text>
        <v-carousel
          show-arrows="hover"
          cycle
          :value="0"
          progress
        >
          <v-carousel-item
            v-for="(image, i) in car.files"
            :key="i"
            :src="'/storage/app/public/files/'+image"
            cover
          />
        </v-carousel>
      </v-card-text>
      <v-card-actions
        class="d-flex flex-row-reverse"
        theme="dark"
      >
        <v-btn
          class="ma-2 pa-2"
          variant="tonal"
          color="blue-grey-lighten-5"
          prepend-icon="mdi-delete"
          @click="showAlertToDelete"
        >
          Eliminar
          <template #prepend>
            <v-icon color="error" />
          </template>
        </v-btn>
        <v-btn
          class="ma-2 pa-2"
          variant="tonal"
          color="blue-grey-lighten-5"
          prepend-icon="mdi-checkbox-marked-circle-outline"
          @click="markSold(car.id)"
        >
          {{ car.sold == true ? 'A la venta' : 'Vendido' }}
          <template #prepend>
            <v-icon color="success" />
          </template>
        </v-btn>
        <v-btn
          class="ma-2 pa-2"
          variant="tonal"
          color="blue-grey-lighten-5"
          prepend-icon="mdi-pencil"
          @click="editCar(car.id)"
        >
          Editar
          <template #prepend>
            <v-icon color="info" />
          </template>
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script>
import axios from 'axios'
export default {
	data() {
		return {
			car: [],
			successMessage: '',
			showSnackbar: false
		}
	},
	mounted() {
		this.getCar()
	},
	methods: {
		getCar() {
			axios.get('/car/show/'+this.$route.params.id).
				then(response => {
					this.car = response.data
				}).
				catch(error => {
					console.log(error)
				})
			if (this.$route.query.success) {
				this.successMessage = this.$route.query.message
				this.showAlert()
			}
		},
		editCar(id) {
			this.$router.push('/editar/vehiculo/'+id)
		},
		async markSold(id) {
			try {
				const response = await axios.put(`/car/sold/${id}`)
				this.getCar()
				this.showSoldAlert(response.data.sold)
			} catch(error) {
				console.log(error)
			}
		},
		async deleteCar(id) {
			try {
				const response = await axios.delete(`/car/delete/${id}`)
				this.$router.push({ 
					path: '/inventario',
					query: { 
						success: response.data.success,
						message: response.data.message
					} 
				})
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
		},
		showAlertToDelete(){
			this.$swal({
				title: 'Está seguro?',
				text: '¡No podrás revertirlo!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Corfirmar',
				cancelButtonText: 'Cancelar',
				backdrop: 'rgba(231, 228, 228,0.4)'
			}).
				then((result) => {
					if (result.isConfirmed) {
						this.deleteCar(this.car.id)
					}
				})
		},
		downloadImages(){
			for(let i = 0; i < this.car.files.length; i++){
				const link = document.createElement('a')
				link.href = '/storage/app/public/files/'+this.car.files[i]
				link.download = '/storage/app/public/files/'+this.car.files[i]
				link.click()
			}
		},
		copyToClipboard() {
			navigator.clipboard.writeText(this.car.description)
		},
		copyTextToClipboard(){
			navigator.clipboard.writeText(this.car.description)
			this.showSnackbar = true
		}
	}
}
</script>
