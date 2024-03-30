import sys
import numpy as np
import cv2
import tensorflow as tf
import json

if len(sys.argv) != 2:
    print(json.dumps({"error": "Invalid number of arguments"}))
    sys.exit(1)

image_path = sys.argv[1]
image = cv2.imread(image_path)

if image is None:
    print(json.dumps({"error": "Failed to load the image"}))
    sys.exit(1)

image_resized = cv2.resize(image, (224, 224))
cv2.imwrite('resize_image.jpg', image_resized)

image_normalized = image_resized.astype('float32') / 255.0
image_normalized_to_show = (image_normalized * 255).astype('uint8')
cv2.imwrite('normalized_image.jpg', image_normalized_to_show)

model_path = "/home/users/2/deca.jp-broad-hyuga-0115/web/fcdb3/app/Python/model/0328_e40_ft15_ml17.tflite"
try:
    interpreter = tf.lite.Interpreter(model_path=model_path)
    interpreter.allocate_tensors()
except Exception as e:
    print(json.dumps({"error": "Failed to load model", "details": str(e)}))
    sys.exit(1)

input_details = interpreter.get_input_details()
output_details = interpreter.get_output_details()

input_data = np.expand_dims(image_normalized, axis=0)
interpreter.set_tensor(input_details[0]['index'], input_data)

interpreter.invoke()
output_data = interpreter.get_tensor(output_details[0]['index'])


#class_names = ['2042107','2042124','2042129','2042148','2042155','2042179', '2042204','2042213']
class_names = ['2042107','2042124','2042129','2042148','2042155','2042179', '2042204','2042213','2142122','2142149','2142163','2142174','2142281','2142291','2194201','2294201','2294207']
results = [{"class": class_names[idx], "softmax_value": float(output_data[0][idx])} for idx in np.argsort(output_data[0])[::-1]]
print(json.dumps(results))
