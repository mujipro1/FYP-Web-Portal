import os

def create_js_file(image_folder, js_file):
    crops = []

    for file_name in os.listdir(image_folder):
        if file_name.endswith(('.jpg', '.jpeg', '.png', '.gif')):
            crop_name = os.path.splitext(file_name)[0]
            crop_name = crop_name.capitalize()  # Capitalize the crop name
            crops.append({'name': crop_name, 'image': f'/images/crops/{file_name}'})
    
    with open(js_file, 'w') as file:
        file.write('const crops = [\n')
        for crop in crops:
            file.write(f"    {{ name: '{crop['name']}', image: '{crop['image']}' }},\n")
        file.write('];\n\n')
        file.write('export default crops;\n')

if __name__ == "__main__":
    image_folder = 'public/images/crops'  # Replace with the path to your image folder
    js_file = 'resources/js/components/cropdata.js'  # Replace with the path to your output JS file
    create_js_file(image_folder, js_file)
