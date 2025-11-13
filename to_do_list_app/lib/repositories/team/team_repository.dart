import 'package:to_do_list_app/models/auth_response.dart';
import 'package:to_do_list_app/models/team.dart';
import 'package:to_do_list_app/services/auth_service.dart';

class ReqTeamDTO {
  final int? id;
  final String name;
  final List<TeamMember>? teamMembers;
  ReqTeamDTO({required this.id, required this.name, this.teamMembers});
  Map<String, dynamic> toJson() {
    Map<String, dynamic> json = {'id': id, 'name': name};
    if (teamMembers != null && teamMembers!.isNotEmpty) {
      json['teamMembers'] = teamMembers!.map((member) => {
        'userId': member.userId,
        'role': member.role.toString().split('.').last,
      }).toList();
    }
    return json;
  }
}

class TeamRepository {
  final AuthService authService;
  TeamRepository(this.authService);
  String path = '/api/v1/team';

  Future<List<Team>> getTeamsByUserId(int user_id) async {
    final response = await authService.get('$path/$user_id');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      List<dynamic> data = response.data['data'];
      return data.map((team) => Team.fromJson(team)).toList();
    } else {
      throw Exception('Lỗi khi tải danh sách nhóm');
    }
  }

  Future<Team> getTeamById(int teamId) async {
    final response = await authService.get('$path/detail/$teamId');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      final data = response.data['data'];
      return Team.fromJson(data);
    } else {
      throw Exception('Lỗi khi tải danh sách nhóm');
    }
  }

  Future<int?> createTeam(ReqTeamDTO reqTeamDTO, int userId) async {
    try {
      final response = await authService.post(
        '$path/$userId',
        reqTeamDTO.toJson(),
      );

      // Laravel returns 201 for successful creation, 200 for other success
      if (response.statusCode == 200 || response.data['status'] == 201) {
        final data = response.data['data'];
        final team = Team.fromJson(data);
        return team.id;
      } else {
        // Log error for debugging
        print('Failed to create team: ${response.statusCode}');
        print('Response: ${response.data}');
        throw Exception(
          response.data?['message'] ?? 
          'Lỗi khi tạo nhóm: Status ${response.statusCode}'
        );
      }
    } catch (e) {
      print('Error in createTeam: $e');
      rethrow;
    }
  }

  Future<void> updateTeam(ReqTeamDTO reqTeamDTO) async {
    final response = await authService.put('$path', reqTeamDTO.toJson());
    if (response.statusCode != 200 || response.data['status'] != 200) {
      throw Exception('Lỗi khi cập nhật nhóm');
    }
  }

  Future<void> deleteTeam(int id) async {
    final response = await authService.delete('$path/$id');
    if (response.statusCode != 200 || response.data['status'] != 200) {
      throw Exception('Lỗi khi xóa nhóm');
    }
  }

  Future<void> deleteTeamAndTask(int id) async {
    final response = await authService.delete('$path/task/$id');
    if (response.statusCode != 200 || response.data['status'] != 200) {
      throw Exception('Lỗi khi xóa nhóm');
    }
  }
  Future<User> getUserbyEmail(String Email) async {
    // URL encode email to handle special characters
    final encodedEmail = Uri.encodeComponent(Email);
    final response = await authService.get('/api/v1/user/by-email/$encodedEmail');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      final data = response.data['data'];
      return User.fromJson(data);
    } else {
      // Log error for debugging
      print('Failed to get user by email: ${response.statusCode}');
      print('Response: ${response.data}');
      throw Exception(
        response.data?['message'] ?? 
        'Lỗi khi tải người dùng email: $Email - Status ${response.statusCode}'
      );
    }
  }

  Future<List<User>> searchUsersByEmailPrefix(String prefix, {int limit = 10}) async {
    final response = await authService.get('/api/v1/user/search?prefix=${Uri.encodeComponent(prefix)}&limit=$limit');
    if (response.statusCode == 200 && response.data['status'] == 200) {
      List<dynamic> data = response.data['data'];
      return data.map((user) => User.fromJson(user)).toList();
    } else {
      throw Exception('Lỗi khi tìm kiếm người dùng');
    }
  }
}