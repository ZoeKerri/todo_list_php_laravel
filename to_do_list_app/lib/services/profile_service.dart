import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ProfileService {
  final Dio dio = Dio(
    BaseOptions(
      baseUrl: "http://10.0.2.2:8080",
      validateStatus: (status) {
        return true;
      },
    ),
  );

  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  Future<Map<String, String>> _getAuthHeaders() async {
    String? token = await _storage.read(key: 'access_token');
    if (token == null) {
      throw Exception('No access token found');
    }
    return {'Authorization': 'Bearer $token'};
  }

  // uploadFile vẫn cần FormData vì xử lý file
  Future<String> uploadFile(Map<String, dynamic> params) async {
    final filePath = params['filePath'] as String?;
    // Laravel doesn't use 'folder' parameter, it stores in 'uploads' folder

    if (filePath == null) {
      throw Exception('Missing required parameter: filePath');
    }

    final headers = await _getAuthHeaders();
    final formData = FormData.fromMap({
      'file': await MultipartFile.fromFile(filePath),
      // Laravel doesn't need 'folder' parameter
    });

    try {
      final response = await dio.post(
        '/api/v1/file/upload', // Laravel uses /file/upload, not /files
        data: formData,
        options: Options(headers: headers, contentType: 'multipart/form-data'),
      );

      if (response.statusCode == 200 && response.data['status'] == 200 && response.data['data'] != null) {
        // Laravel FileUploadDTO returns: url, fileName (original_name), fileSize
        // We need the file path from url or construct it from file_path
        final data = response.data['data'];
        // FileUploadDTO returns url which is the full path: asset('storage/' . $fileUpload->file_path)
        // But we need just the file path relative to storage for saving to user avatar
        final url = data['url'] as String?;
        final fileName = data['fileName'] as String?; // This is original_name
        
        if (url != null) {
          // Extract file path from URL: http://localhost:8080/storage/uploads/uuid.ext
          // We need: uploads/uuid.ext
          final uri = Uri.parse(url);
          final pathSegments = uri.pathSegments;
          // Find 'storage' and get everything after it
          final storageIndex = pathSegments.indexOf('storage');
          if (storageIndex >= 0 && storageIndex < pathSegments.length - 1) {
            final filePath = pathSegments.sublist(storageIndex + 1).join('/');
            print('File uploaded successfully, path: $filePath');
            return filePath; // Return relative path: uploads/uuid.ext
          }
        }
        
        // Fallback: if url parsing fails, try to get from fileName
        if (fileName != null) {
          // This is not ideal as fileName is original_name, not the stored filename
          // But we'll use it as fallback
          print('File uploaded successfully (using fileName fallback): $fileName');
          return fileName;
        }
        
        throw Exception('File path not found in response: ${response.data['data']}');
      } else {
        throw Exception(
          response.data?['message'] ?? 
          'Failed to upload file: ${response.statusCode}'
        );
      }
    } catch (e) {
      throw Exception('Failed to upload file: $e');
    }
  }

  // updateProfile sử dụng JSON thay vì FormData
  Future<void> updateProfile(Map<String, dynamic> params) async {
    final name = params['name'] as String?;
    final phone = params['phone'] as String?;
    final avatarImagePath = params['avatarImagePath'] as String?;

    if (name == null) {
      throw Exception('Missing required parameter: name');
    }

    final headers = await _getAuthHeaders();
    
    // Laravel updateProfile supports: full_name, email, phone, avatar, password
    final data = {
      'full_name': name, // Laravel expects full_name, not name
      if (phone != null) 'phone': phone,
      if (avatarImagePath != null) 'avatar': avatarImagePath,
    };

    try {
      final response = await dio.put(
        '/api/v1/user/profile', // Laravel uses /user/profile, not /user/$userId
        data: data,
        options: Options(headers: headers, contentType: 'application/json'),
      );

      print('Update profile response: ${response.data}');

      if (response.statusCode != 200 || response.data['status'] != 200) {
        throw Exception(
          response.data?['message'] ?? 
          'Failed to update profile: ${response.statusCode}'
        );
      }
    } catch (e) {
      throw Exception('Failed to update profile: $e');
    }
  }

  // changePassWord sử dụng JSON thay vì FormData
  Future<void> changePassWord(Map<String, dynamic> params) async {
    final newPassword = params['newPassword'] as String?;
    final oldPassword = params['oldPassword'] as String?;

    if (newPassword == null || oldPassword == null) {
      throw Exception(
        'Missing required parameters: newPassword or oldPassword',
      );
    }

    final headers = await _getAuthHeaders();
    // Laravel uses current_password and password (not oldPassword/newPassword)
    // Also requires password_confirmation
    final data = {
      'current_password': oldPassword,
      'password': newPassword,
      'password_confirmation': newPassword,
    };

    try {
      final response = await dio.put(
        '/api/v1/user/profile', // Laravel uses /user/profile for password change too
        data: data,
        options: Options(headers: headers, contentType: 'application/json'),
      );

      print('Change password response: ${response.data}');

      if (response.statusCode != 200 || response.data['status'] != 200) {
        throw Exception(
          response.data?['message'] ?? 
          'Failed to change password: ${response.statusCode}'
        );
      }
    } catch (e) {
      throw Exception('Failed to change password: $e');
    }
  }
}
