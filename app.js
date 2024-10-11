// JavaScript
const dropZone = document.getElementById('drop-zone')
const selectFilesBtn = document.getElementById('select-files')
const fileInput = document.getElementById('drop-file')
const fileList = document.getElementById('file-list')
const sendBtn = document.getElementById('send-btn')

// Arreglo para almacenar los archivos seleccionados
let selectedFiles = []

// Manejador de eventos para el elemento de entrada de archivos
selectFilesBtn.addEventListener('click', () => {
	fileInput.click()
})
fileInput.addEventListener('change', (event) => {
	handleFiles(event.target.files)
})

// Manejador de eventos para el área de arrastrar y soltar
dropZone.addEventListener('dragover', (event) => {
	event.preventDefault()
})
dropZone.addEventListener('drop', (event) => {
	event.preventDefault()
	handleFiles(event.dataTransfer.files)
})

// Función para manejar los archivos seleccionados
function handleFiles(files) {
	for (const file of files) {
		if (file.type !== 'application/pdf') {
			alert('Solo se permiten archivos PDF.')
			continue
		}

		const existingFile = selectedFiles.find((f) => f.name === file.name)
		if (existingFile) {
			alert(
				`${file.name} ya ha sido seleccionado. Por favor, elige otro archivo.`,
			)
			continue
		}
		const li = document.createElement('li')
		li.classList.add('file-item')
		li.textContent = file.name

		const deleteBtn = document.createElement('button')
		deleteBtn.classList.add('delete-btn')
		deleteBtn.textContent = 'X'
		deleteBtn.addEventListener('click', () => {
			li.remove()
			// Eliminar el archivo del arreglo de archivos
			selectedFiles = selectedFiles.filter((f) => f.name !== file.name)
		})

		li.appendChild(deleteBtn)
		fileList.appendChild(li)

		selectedFiles.push(file)
	}

	// Habilitar el botón de enviar si hay archivos seleccionados
	sendBtn.disabled = selectedFiles.length === 0
}

sendBtn.addEventListener('click', () => {
	const form = document.getElementById('myForm')
	const formData = new FormData(form)
	for (const file of selectedFiles) {
		formData.append('files[]', file)
	}
	fetch('/upload.php', {
		method: 'POST',
		body: formData,
	})
		.then((response) => {
			if (!response.ok) {
				throw new Error('Error al subir los archivos')
			}
			return response.text()
		})
		.then((data) => {
			console.log('Respuesta del servidor:', data)
			location.reload()
		})
		.catch((error) => {
			console.error('Error:', error)
		})
})
