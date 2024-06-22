import os

def capitalize_first_letter_of_images(directory):
    # Supported image extensions
    supported_extensions = ('.png', '.jpg', '.jpeg', '.gif', '.bmp', '.tiff')

    # List all files in the directory
    for filename in os.listdir(directory):
        # Check if the file is an image
        if filename.lower().endswith(supported_extensions):
            # Get the full file path
            old_filepath = os.path.join(directory, filename)
            
            # Capitalize the first letter of the filename
            new_filename = filename.capitalize()
            new_filepath = os.path.join(directory, new_filename)

            # Rename the file
            os.rename(old_filepath, new_filepath)
            print(f'Renamed: {old_filepath} -> {new_filepath}')

# Example usage
image_folder = 'public/images/crops'  # Replace with the path to your image folder
capitalize_first_letter_of_images(image_folder)







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

js_file = 'resources/js/components/cropdata.js'  # Replace with the path to your output JS file
create_js_file(image_folder, js_file)


