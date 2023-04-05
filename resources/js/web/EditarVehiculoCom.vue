<template>
  <v-container>
    <v-form @submit.prevent="updateRecord">
      <v-textarea
        v-model="car.description"
        label="Descripción"
        :counter="300"
        required
      />
      <v-row>
        <v-col cols="auto">
          <v-checkbox
            v-model="car.financed"
            label="Financiado"
          />
        </v-col>
        <v-col cols="auto">
          <v-checkbox
            v-model="car.visible"
            label="Visible"
          />
        </v-col>
        <v-col cols="auto">
          <v-checkbox
            v-model="car.sold"
            label="Vendido"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col
          v-for="(image, index) in car.files"
          :key="index"
          cols="3"
        >
          <v-card
            class="mx-auto"
            max-width="344"
            hover
          >
            <v-img
              :src="'/storage/app/public/files/' + image"
              height="200"
            >
              <v-btn
                v-if="car.files.length > 1"
                color="error" 
                fab 
                dark 
                small 
                class="ma-2" 
                @click="removeImage(index, image, car.id)"
              >
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </v-img>
          </v-card>
        </v-col>
      </v-row>
      <v-file-input
        v-model="selectedFiles"
        label="Cargar imágenes"
        multiple
        accept="image/*"
        class="mt-4"
        @change="uploadFiles(car.id)"
      />
      <v-btn @click="updateRecord">
        <b>Guardar cambios</b>
      </v-btn>
      <v-alert
        v-if="errors.length > 0"
        class="mt-4"
        variant="outlined"
        type="error"
        prominent
        border="top"
      >
        <ul 
          v-for="error in errors" 
          :key="error"
        >
          <li>{{ error }}</li>
        </ul>
      </v-alert>
    </v-form>
  </v-container>
</template>

<script>
import axios from 'axios'
export default {
	data() {
		return {
			car: [],
			errors: [],
			selectedFiles: []
		}
	},
	mounted() {
		axios.get('/car/show/'+this.$route.params.id).
			then(response => {
				this.car = response.data
			}).
			catch(error => {
				console.log(error)
			})
	},
	methods: {
		async removeImage(index, image, id) {
			this.errors = []
			const formData = new FormData()
			formData.append('image', image)
			try {
				const response = await axios.post('/delete/image/'+id, formData)
				this.car.files = response.data
			} catch (error) {
				this.errors = Object.values(error.response.data.errors).flat()
			} finally {
				this.toastRemoved()
			}
            
		},
		async uploadFiles(id) {
			this.errors = []
			const formData = new FormData()
			for (let i = 0; i < this.selectedFiles.length; i++) {
				formData.append('filenames[]', this.selectedFiles[i])
			}
			try {
				const response = await axios.post('/upload/image/'+id, formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				})
				this.car = response.data
			} catch (error) {
				this.errors = Object.values(error.response.data.errors).flat()
			} finally {
				this.toastUploaded(this.selectedFiles.length)
				this.selectedFiles = []
			}
		},
		toastUploaded(arrayLength) {
			this.$swal({
				toast: true,
				position: 'top-end',
				icon: 'success',
				title: arrayLength > 1 ? 'Imagenes cargadas!' : 'Imagen cargada!',
				showConfirmButton: false,
				timer: 2000
			})
		},
		toastRemoved() {
			this.$swal({
				toast: true,
				position: 'top-end',
				icon: 'info',
				title: 'Imagen eliminada!',
				showConfirmButton: false,
				timer: 2000
			})
		},
		async updateRecord() {
			this.errors = []
			try {
				const response = await axios.put('/car/update/'+this.car.id, {
					description: this.car.description,
					financed: this.car.financed === false ? 0 : 1,
					sold: this.car.sold === false ? 0 : 1,
					visible: this.car.visible === false ? 0 : 1
				})
				this.$router.push({ 
					path: '/ver/vehiculo/'+response.data.car.id,
					query: { 
						success: response.data.success,
						message: response.data.message
					} 
				})
			} catch (error) {
				this.errors = Object.values(error.response.data.errors).flat()
			}
		}
	}
}
</script>
