import 'package:to_do_list_app/models/team.dart';
import 'package:to_do_list_app/services/auth_service.dart';

class TeamTaskRepository {
  final AuthService authService;
  TeamTaskRepository(this.authService);
  String path = '/api/v1/team-task';

  Future<List<TeamTask>> getTeamTasksByTeamId(int teamId) async {
    final response = await authService.get('$path/by-team/$teamId');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      List<dynamic> data = response.data['data'];
      return data.map((team) => TeamTask.fromJson(team)).toList();
    } else {
      throw Exception('Lỗi khi tải task nhóm');
    }
  }
  Future<List<TeamTask>> getTeamTasksByUserId(int teamId) async {
    final response = await authService.get('$path/by-user/$teamId');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      List<dynamic> data = response.data['data'];
      return data.map((team) => TeamTask.fromJson(team)).toList();
    } else {
      throw Exception('Lỗi khi tải task nhóm');
    }
  }

  Future<List<TeamTask>> getTeamTasksByMemberId(int memberId) async {
    final response = await authService.get('$path/by-member/$memberId');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      List<dynamic> data = response.data['data'];
      return data.map((team) => TeamTask.fromJson(team)).toList();
    } else {
      throw Exception('Lỗi khi tải task của thành viên nhóm');
    }
  }

  Future<void> createPersonalTask(TeamTask reqTeamTaskDTO) async {
    final response = await authService.post('$path', reqTeamTaskDTO.toJson());
    // Laravel returns 201 for successful creation, 200 for other success
    if (response.statusCode == 200 || response.data['status'] == 201) {
      return; // Success
    }
    // If we get here, it's an error
    print('Failed to create team task: ${response.statusCode}');
    print('Response: ${response.data}');
    throw Exception(
      response.data?['message'] ?? 
      'Lỗi khi thêm task nhóm: Status ${response.statusCode}'
    );
  }

  Future<bool> updatePersonalTask(TeamTask reqTeamTaskDTO) async {
    try {
      final response = await authService.put('$path', reqTeamTaskDTO.toJson());
      // Laravel returns 200 for successful update
      if (response.statusCode == 200 && response.data['status'] == 200) {
        return true;
      } else {
        // Log error for debugging
        print('Failed to update team task: ${response.statusCode}');
        print('Response: ${response.data}');
        return false;
      }
    } catch (e) {
      print('Error updating team task: $e');
      return false;
    }
  }

  Future<void> deletePersonalTask(int id) async {
    final response = await authService.delete('$path/$id');
    if (response.statusCode != 200 || response.data['status'] != 200) {
      throw Exception('Lỗi khi xóa task nhóm');
    }
  }
}
