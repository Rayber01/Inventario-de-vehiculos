<template>
  <v-container>
    <v-form @submit.prevent="submit">
      <v-textarea
        v-model="description"
        label="Descripción"
        :counter="300"
        required
      />
      <v-checkbox
        v-model="financed"
        label="Financiado"
      />
      <v-checkbox
        v-model="visible"
        label="Visible"
      />
      <v-file-input
        v-model="selectedFiles"
        label="Cargar imágenes"
        multiple
        accept="image/*"
      />
      <v-btn type="submit">
        <b>Cargar</b>
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
          v-for="(error, index) in errors"
          :key="index"
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
			description: '',
			financed: false,
			visible: false,
			selectedFiles: [],
			errors: []
		}
	},
	methods: {
		async submit() {
			this.errors = []
			const formData = new FormData()
			formData.append('description', this.description)
			formData.append('financed', this.financed === false ? 0 : 1)
			formData.append('visible', this.visible === false ? 0 : 1)
			for (let i = 0; i < this.selectedFiles.length; i++) {
				formData.append('filenames[]', this.selectedFiles[i])
			}
			try {
				const response = await axios.post('/car/store', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
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
			} finally {
				this.uploadProgress = null
			}
		}
	}
}
</script>
